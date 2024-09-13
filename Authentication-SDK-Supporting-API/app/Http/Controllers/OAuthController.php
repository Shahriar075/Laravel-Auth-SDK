<?php

namespace App\Http\Controllers;

use App\Services\AuthProviderFactory;
use App\Services\OAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class OAuthController extends Controller
{
    public function redirectToProvider($provider, Request $request)
    {
        $oAuthProviderService = AuthProviderFactory::create($provider);

        if($oAuthProviderService instanceof JsonResponse){
            return $oAuthProviderService;
        }

        return $oAuthProviderService->redirectToProvider($request);
    }

    public function handleProviderCallback($provider)
    {
        $oAuthProviderService = AuthProviderFactory::create($provider);

        if($oAuthProviderService instanceof JsonResponse){
            return $oAuthProviderService;
        }

        return $oAuthProviderService->handleProviderCallback();
    }

    public function refreshToken(Request $request)
    {
        $oAuthProviderService = new OAuthService();
        return $oAuthProviderService->refreshToken($request);
    }

}
