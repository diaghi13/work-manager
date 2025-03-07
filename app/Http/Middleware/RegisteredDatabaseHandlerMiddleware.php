<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RegisteredDatabaseHandlerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Filament::auth()->getUser();

        if (!$user || !$user->database) {
            return $next($request);
        }

        if ($request->getRequestUri() == '/admin/logout') {
            return $next($request);
        }

        config()->set('database.connections.mysql.database', $user->database);

        DB::purge('mysql');

        return $next($request);
    }
}
