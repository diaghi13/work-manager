<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/v1/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: 'api/v1',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
//            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
//            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
//            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
//        ]);
//
//        $middleware->priority([
//            \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
//            \Illuminate\Cookie\Middleware\EncryptCookies::class,
//            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//            \Illuminate\Session\Middleware\StartSession::class,
//            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
//            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
//            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
//            \Illuminate\Routing\Middleware\ThrottleRequests::class,
//            \Illuminate\Routing\Middleware\ThrottleRequestsWithRedis::class,
//            \Illuminate\Routing\Middleware\SubstituteBindings::class,
//            \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
//            \Illuminate\Auth\Middleware\Authorize::class,
//        ]);
//
//        $middleware->web(append: [
//            //\App\Http\Middleware\RegisteredDatabaseHandlerMiddleware::class,
        ]);

//        $middleware->trustProxies(at: [
//            'https://work-manager.ddns.net/',
//            'http://192.168.1.3/'
//        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
