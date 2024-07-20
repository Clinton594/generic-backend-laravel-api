<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CustomAccessDeniedHttpException extends Exception
{

    static function render(AccessDeniedHttpException $e): JsonResponse
    {
        $code = 422;
        return new JsonResponse([
            'code' => $code,
            'status' => false,
            'message' => $e->getMessage(),

        ], $code);
    }
}
