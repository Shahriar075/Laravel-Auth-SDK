<?php


namespace App\Exceptions;

class Handler
{
    public function render($request, \Throwable $exception)
    {
        if($exception instanceof \Illuminate\Auth\AuthenticationException){
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return parent::render($request, $exception);
    }
}
