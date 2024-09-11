<?php

namespace App\Services;

use App\Exceptions\ExceptionHandler;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class OAuthService implements AuthProviderInterface
{
    private $provider;

    public function __construct($provider = null)
    {
        $this->provider = $provider;
    }
    public function redirectToProvider(Request $request)
    {
        try {
            $config = [
                'client_id' => $request->input('client_id'),
                'client_secret' => $request->input('client_secret'),
                'redirect' => $request->input('redirect_uri'),
            ];

            config([
                "services.{$this->provider}.client_id" => $config['client_id'],
                "services.{$this->provider}.client_secret" => $config['client_secret'],
                "services.{$this->provider}.redirect" => $config['redirect'],
            ]);

            $socialite = Socialite::driver($this->provider);
            $redirectUrl = $socialite->stateless()->redirect()->getTargetUrl();

            return response()->json(['redirect_url' => $redirectUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error during redirecting to ' . $this->provider], 400);
        }
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver($this->provider)->user();

            $existingUser = User::where('provider_id', $user->id)
                ->where('provider', $this->provider)
                ->first();

            if ($existingUser) {
                Auth::login($existingUser);
                $token = $this->generateToken($existingUser);

                $refreshToken = $this->generateRefreshToken();

                UserToken::updateOrCreate(
                    ['user_id' => $existingUser->id],
                    ['token' => $token, 'refresh_token' => $refreshToken ]
                );

                return response()->json(['token' => $token]);
            }

            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'provider' => $this->provider,
                'provider_id' => $user->getId(),
                'password' => Hash::make('password'),
                'role_id' => 1,
            ]);

            Auth::login($newUser);
            $token = $this->generateToken($newUser);

            UserToken::create([
                'user_id' => $newUser->id,
                'token' => $token,
            ]);

            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return ExceptionHandler::handle($e, 'Authentication failed', 400);
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

    private function generateToken($user)
    {
        return JWTAuth::fromUser($user);
    }

    private function generateRefreshToken()
    {
        return Str::random(8);
    }
}
