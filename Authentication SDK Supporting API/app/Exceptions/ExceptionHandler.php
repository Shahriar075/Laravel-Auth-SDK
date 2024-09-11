<?php

namespace App\Exceptions;

class ExceptionHandler{

    public static function handle(\Exception $e, $message = "An error occurred", $statusCode = 500)
    {
        return response()->json([
            'error' => $message,
            'detail' => $e->getMessage(),
        ], $statusCode);
    }
}
