<?php

namespace App\Services;

use App\Http\Controllers\OAuthController;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Two\GoogleProvider;

class AuthProviderFactory
{
    public static function create($provider): AuthProviderInterface | JsonResponse
    {
        switch ($provider) {
            case 'google':
            case 'github':
                return new OAuthService($provider);
            default:
                return response()->json(['error' => 'Unsupported provider'], 400);
        }
    }
}
