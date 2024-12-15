<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::check()) {
            $userRole = Auth::user()->role;
    
            // Allow 'admin' role to bypass all other role checks
            if ($userRole === 'admin' || $userRole === $role) {
                return $next($request);
            }
        }
    
        // If the user is not authenticated or does not have the required role, deny access
        abort(403, 'Unauthorized access');
    }
}
