<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Check if the user's role is allowed
        // The ...$roles allows us to pass multiple roles like: 'admin,sub_admin'
        $userRole = Auth::user()->role;

        if (!in_array($userRole, $roles)) {
            // If the user doesn't have the right role, show 403 Forbidden
            abort(403, 'Unauthorized action. You do not have the required role.');
        }

        return $next($request);
    }
}