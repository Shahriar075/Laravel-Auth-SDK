<?php

namespace AuthSDKLibrary;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class AuthManager
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api.base_url');
    }

    /**
     * Register a new user.
     *
     * @param array $data {
     *     @type string $name     The name of the user.
     *     @type string $email    The email of the user.
     *     @type string $password The password for the user (must be confirmed).
     *     @type int    $role_id  The role ID for the user (optional).
     * }
     * @return \Illuminate\Http\JsonResponse
     */

    public static function register(array $data): JsonResponse
    {
        $response = Http::post(AuthManager::getBaseUrl() . "/register", $data);

        return response()->json($response->json());
    }

    /**
     * Login a user.
     *
     * @param array $credentials {
     *     @type string $email    The email of the user.
     *     @type string $password The password for the user.
     * }
     * @return \Illuminate\Http\JsonResponse
     */
    public static function login(array $credentials): JsonResponse
    {
        $response = Http::post(AuthManager::getBaseUrl() . "/login", $credentials);

        return response()->json($response->json());
    }

    /**
     * Refresh the user's token.
     *
     * @param string $refreshToken The refresh token to use.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function refreshToken(string $refreshToken): JsonResponse
    {
        $response = Http::post(AuthManager::getBaseUrl() . "/auth/refresh", [
            'refresh_token' => $refreshToken
        ]);

        return response()->json($response->json());
    }

    /**
     * Assign a role to a user.
     *
     * @param array $data {
     *     @type string $name The name of a role.
     * }
     * @return \Illuminate\Http\JsonResponse
     */
    public static function addRoleForUser(array $data): JsonResponse
    {
        $response = Http::post(AuthManager::getBaseUrl() . "/add-user-role", $data);

        return response()->json($response->json());
    }

    public static function getBaseUrl()
    {
        return config('services.api.base_url');
    }
}
