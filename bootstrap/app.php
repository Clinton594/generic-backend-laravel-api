<?php

use App\Exceptions\CustomAccessDeniedHttpException;
use App\Exceptions\CustomAuthenticationException;
use App\Exceptions\CustomBadMethodCallException;
use App\Exceptions\CustomMethodNotAllowedHttpException;
use App\Exceptions\CustomModelNotFoundException;
use App\Exceptions\CustomNotFoundHttpException;
use App\Exceptions\CustomValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Form Validation Exception
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            return  CustomValidationException::render($e);
        });

        // Method not allowed Request
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            return  CustomMethodNotAllowedHttpException::render($e);
        });

        // Authorizability
        $exceptions->renderable(function (AccessDeniedHttpException $e, Request $request) {
            return  CustomAccessDeniedHttpException::render($e);
        });

        // Sanctum Authentication Exception
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            return  CustomAuthenticationException::render($e);
        });

        // Sanctum Authentication Exception
        $exceptions->renderable(function (BadMethodCallException $e, Request $request) {
            return  CustomBadMethodCallException::render($e);
        });

        // Model not found
        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            return  CustomModelNotFoundException::render($e);
        });

        // Route not found
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            return  CustomNotFoundHttpException::render($e);
        });
    })->create();
