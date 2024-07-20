<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class CustomMethodNotAllowedHttpException extends Exception
{

    static function render(MethodNotAllowedHttpException $e): JsonResponse
    {
        $code = 405;
        return new JsonResponse([
            'code' => $code,
            'status' => false,
            'message' => $e->getMessage(),

        ], $code);
    }
}
