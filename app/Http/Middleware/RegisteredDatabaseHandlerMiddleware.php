<?php

namespace App\Http\Middleware;

use App\Exceptions\Tenant\TenantDatabaseNotProvidedException;
use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RegisteredDatabaseHandlerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws TenantDatabaseNotProvidedException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->method() !== 'OPTIONS') {
            //return $next($request);
        }

        if (!auth()->check()) {
            return $next($request);
        }

//        if ($request->getRequestUri() == '/admin/logout') {
//            return $next($request);
//        }

        $database = $this->findDatabase();

        if (!$database) {
            return $next($request);
        }

//        config()->set('database.connections.mysql.database', $user->database);
//
//        DB::purge('mysql');

        Tenant::switch(false, $database);

        return $next($request);
    }

    public function findDatabase()
    {
        $user = auth()->getUser();
        return $user->tenants()->wherePivot('selected', true)->first()->id;
    }
}
