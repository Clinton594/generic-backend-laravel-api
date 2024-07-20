<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomNotFoundHttpException extends Exception
{

    static function render(NotFoundHttpException $e): JsonResponse
    {
        $code = 404;
        return new JsonResponse([
            'code' => $code,
            'status' => false,
            'message' => $e->getMessage(),
        ], $code);
    }
}
