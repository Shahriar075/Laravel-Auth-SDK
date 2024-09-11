<?php

namespace App\Http\Controllers;

use App\Exceptions\ExceptionHandler;
use App\Models\Role;
use App\Models\User;
use App\Models\UserToken;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role_id' => 'nullable|exists:roles,id'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => $validatedData['role_id'],
            ]);

            return response()->json(['user' => $user], 201);
        } catch (\Exception $e) {
            return ExceptionHandler::handle($e,  'Registration failed', 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try{
            $user = $this->authService->authenticate($credentials);
            $token = $this->generateToken($user);

            $refreshToken = $this->generateRefreshToken();

            UserToken::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'token' => $token, 'refresh_token' => $refreshToken
                ]
            );
            return response()->json(['token' => $token], 200);
        } catch (\Exception $e){
            return ExceptionHandler::handle($e, 'Login failed', 500);
        }
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->input('refresh_token');

        if (!$refreshToken) {
            return response()->json(['error' => 'Refresh token is missing'], 400);
        }

        $userToken = UserToken::where('refresh_token', $refreshToken)->first();

        if (!$userToken) {
            return response()->json(['error' => 'Refresh token not found'], 401);
        }

        $currentToken = $userToken->token;

        try {
            JWTAuth::setToken($currentToken);
            JWTAuth::checkOrFail($currentToken);

            return response()->json(['message' => 'Token is still valid, no need to refresh'], 400);
        }
        catch (\Exception $e) {

            $user = User::find($userToken->user_id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $newToken = $this->generateToken($user);
            $newRefreshToken = $this->generateRefreshToken();

            $userToken->update([
                'token' => $newToken,
                'refresh_token' => $newRefreshToken,
            ]);

            return response()->json([
                'token' => $newToken,
                'refresh_token' => $newRefreshToken
            ], 200);
        }
    }

    public function addUserRole(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        try {
            Role::create([
                'name' => $validatedData['name'],
            ]);

            return response()->json(['message' => 'Role added successfully'], 201);
        } catch (\Exception $e) {
            return ExceptionHandler::handle($e,  'Role creation failed', 500);
        }
    }

    private function generateToken($user)
    {
        return JWTAuth::fromUser($user);
    }

    private function generateRefreshToken()
    {
        return Str::random(8);
    }

}
