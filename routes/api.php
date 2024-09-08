<?php

use AuthSDKLibrary\AuthManager;
use AuthSDKLibrary\OAuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', function(Request $request) {
    $data = AuthManager::register($request->all());
    return response()->json($data->getData());
});

Route::post('/login', function(Request $request) {
    $data = AuthManager::login($request->all());
    return response()->json($data->getData());
});

Route::post('auth/refresh', function(Request $request) {
    $refreshToken = $request->input('refresh_token');
    $data = AuthManager::refreshToken($refreshToken);

    return response()->json($data->getData());
});

Route::post('/add-user-role', function (Request $request){
    $data = AuthManager::addRoleForUser($request->all());
    return response()->json($data->getData());
});

Route::middleware('web')->group(function () {

    Route::get('auth/{provider}', function ($provider) {

        $config = [
            'client_id' => env('GITHUB_CLIENT_ID'),
            'client_secret' => env('GITHUB_CLIENT_SECRET'),
            'redirect_uri' => env('GITHUB_REDIRECT_URI'),
        ];

        $loginPageUrl = OAuthManager::redirectToProvider($provider, $config);

        return redirect($loginPageUrl);
    });

    Route::get('auth/{provider}/callback', function ($provider) {
        $oAuthManager = new OAuthManager($provider);
        return $oAuthManager->handleProviderCallback();
    });
});

