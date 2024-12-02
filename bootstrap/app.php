<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append()
    })
    ->withExceptions(function (Exceptions $exceptions) {
        if (request()->is('api/*')) {
            $exceptions->render(function (NotFoundHttpException $e, Request $request) {
                return response()->json([
                    'statusCode'      => 404,
                    'message' => "API endpoint not found",
                ]);
            });
            $exceptions->render(function (ValidationException $e, Request $request) {
                return response()->json([
                    'statusCode'      => 422,
                    'message' => $e->errors()
                ], 422);
            });

            $exceptions->render(function (Throwable $exception, Request $request) {

                if (env('APP_DEBUG')) {
                    return response()->json([
                        'statusCode' => 500,
                        'error' => "SERVER ERROR: {$exception->getMessage()} in {$exception->getFile()} on line {$exception->getLine()}",
                    ], 500);
                } else {
                    // Log the error message for production
                    Log::error("SERVER ERROR: {$exception->getMessage()} in {$exception->getFile()} on line {$exception->getLine()}");

                    return response()->json([
                        'statusCode' => 500,
                        'message' => 'Something Went Wrong',
                        'error' => 'Something Went Wrong',
                    ], 500);
                }
            });
        }
    })->create();
