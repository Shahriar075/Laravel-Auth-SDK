<?php

namespace AuthSDKLibrary;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class OAuthManager
{
    protected $baseUrl;
    private $provider;

    public function __construct($provider)
    {
        $this->baseUrl = config('services.api.base_url');
        $this->provider = $provider;
    }

    /**
     * Redirect to the OAuth provider's authentication page.
     *
     * @param string $provider The OAuth provider (e.g., 'google', 'github').
     * @return string The URL of the OAuth provider's login page.
     */
    public static function redirectToProvider(string $provider, array $config): string
    {
        $response = Http::get(OAuthManager::getBaseUrl() . "/auth/{$provider}", $config);

        if ($response->successful()) {
            $data = $response->json();
            return $data['redirect_url'];
        }

        return $response->body();
    }

    /**
     * Handle the OAuth provider's callback
     *
     * @param string $provider The OAuth provider (e.g., 'google', 'github').
     * @param array $data {
     *     @type string $code The authorization code returned by the provider.
     * }
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleProviderCallback()
    {
        try {
            $socialite = Socialite::driver($this->provider);

            $user = $socialite->stateless()->user();

            return response()->json($user);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Refresh the user's token.
     *
     * @param string $refreshToken The refresh token to use.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function refreshToken(string $refreshToken): JsonResponse
    {
        $response = Http::post(OAuthManager::getBaseUrl() . "/oauth/refresh", [
            'refresh_token' => $refreshToken
        ]);

        return response()->json($response->json());
    }

    public static function getBaseUrl()
    {
        return config('services.api.base_url');
    }
}
