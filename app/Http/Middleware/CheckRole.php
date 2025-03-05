<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        // If no role specified, just check if user is authenticated
        if ($role === null) {
            return $next($request);
        }

        if (!$request->user() || !$request->user()->hasRole($role)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
