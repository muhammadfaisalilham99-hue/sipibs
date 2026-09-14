<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()) {
            return redirect()->route($role === 'admin' ? 'admin.login' : 'login');
        }

        if ($role === 'admin' && $request->user()->role !== 'admin') {
            abort(403);
        }

        if ($role === 'user' && $request->user()->role === 'admin') {
            abort(403);
        }

        return $next($request);
    }
}
