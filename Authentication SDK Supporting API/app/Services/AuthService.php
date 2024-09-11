<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService{

    public function authenticate($credentials)
    {
        if(Auth::attempt($credentials)){
            return Auth::user();
        }

        throw new \Exception('Invalid credentials');
    }
}
