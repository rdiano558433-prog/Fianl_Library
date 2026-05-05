<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            abort(403, 'Unauthorized: Not logged in');
        }

        $user = Auth::user();

        // Check if user role matches required role
        if ($user->role !== $role) {
            abort(403, 'Unauthorized role: ' . $user->role);
        }

        return $next($request);
    }
}