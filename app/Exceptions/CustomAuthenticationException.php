<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class CustomAuthenticationException extends Exception
{
    static function render(AuthenticationException $e): JsonResponse
    {
        $code = 401;
        return new JsonResponse([
            'code' => $code,
            'status' => false,
            'message' => $e->getMessage(),

        ], $code);
    }
}
