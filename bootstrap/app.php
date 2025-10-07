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
    ->withMiddleware(function (Middleware $middleware): void {
        // Add our custom middleware to the global middleware stack
        $middleware->prependToGroup('web', \App\Http\Middleware\DisableCsrfForPayments::class);
        
        // Register route middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'ssl.gateway.auth' => \App\Http\Middleware\SslGatewayAuth::class,
            'disable.csrf.payments' => \App\Http\Middleware\DisableCsrfForPayments::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
