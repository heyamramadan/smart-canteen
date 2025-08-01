<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(callback: function (Middleware $middleware) {
         $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
         'adminOrEmployee' => \App\Http\Middleware\AdminOrEmployeeMiddleware::class,

            // ✅ Middleware الخاص بـ Sanctum
            'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
