<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CustomValidationException extends Exception
{

    static function render(ValidationException $e): JsonResponse
    {
        $code = 422;
        return new JsonResponse([
            'code' => $code,
            'status' => false,
            'message' => $e->getMessage(),

        ], $code);
    }
}
