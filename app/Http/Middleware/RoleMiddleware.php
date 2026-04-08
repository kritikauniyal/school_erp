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
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // user not logged in
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role->name;

        // check allowed roles
        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}