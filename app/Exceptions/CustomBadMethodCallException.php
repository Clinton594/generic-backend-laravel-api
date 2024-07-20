<?php

namespace App\Exceptions;

use BadMethodCallException;
use Exception;
use Illuminate\Http\JsonResponse;

class CustomBadMethodCallException extends Exception
{
    static function render(BadMethodCallException $e): JsonResponse
    {
        $code = 500;
        return new JsonResponse([
            'code' => $code,
            'status' => false,
            'message' => $e->getMessage(),

        ], $code);
    }
}
