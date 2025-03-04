<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__ . '/../routes/v1/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api/v1',
    )
    ->withMiddleware(function (Middleware $middleware) {
//        $middleware->trustProxies(at: [
//            'https://work-manager.ddns.net/',
//            'http://192.168.1.3/'
//        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
