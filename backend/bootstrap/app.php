<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Modules\Core\Exceptions\InvalidStatusTransitionException;
use Modules\Core\Exceptions\InspectionNotEditableException;
use Modules\Core\Http\Responses\ApiResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (InvalidStatusTransitionException $e) {
            return ApiResponse::error($e->getMessage(), null, 422);
        });

        $exceptions->render(function (InspectionNotEditableException $e) {
            return ApiResponse::error($e->getMessage(), null, 403);
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            return ApiResponse::notFound('Resource not found.');
        });

        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::unprocessable($e->errors());
        });

    })->create();