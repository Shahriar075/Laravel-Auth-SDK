<?php

use AuthSDKLibrary\AuthManager;

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

Route::get('/auth/{provider}', function($provider) {
    $data = OAuthManager::redirectToProvider($provider);
    return response()->json($data->getData());
});

Route::post('/auth/{provider}/callback', function($provider) {
    $data = OAuthManager::handleProviderCallback($provider);
    return response()->json($data->getData());
});


