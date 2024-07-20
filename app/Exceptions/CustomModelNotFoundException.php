<?php

namespace App\Exceptions;

use Exception;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;



class CustomModelNotFoundException extends Exception
{

    static function render(ModelNotFoundException $e): JsonResponse
    {
        $code = 404;
        return new JsonResponse([
            'code' => $code,
            'status' => false,
            'message' => "Model Not Found",
        ], $code);
    }
}
