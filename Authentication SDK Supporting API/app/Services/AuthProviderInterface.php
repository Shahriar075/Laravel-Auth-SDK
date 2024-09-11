<?php

namespace App\Services;

use Illuminate\Http\Request;

interface AuthProviderInterface
{
    public function redirectToProvider(Request $request);
    public function handleProviderCallback();
}
