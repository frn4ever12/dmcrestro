<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        $user = Auth::user();

        if (!$user->hasRole($role)) {
            return response()->json([
                'message' => 'Unauthorized - insufficient permissions',
            ], 403);
        }

        return $next($request);
    }
}
