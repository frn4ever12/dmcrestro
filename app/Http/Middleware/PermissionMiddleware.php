<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        $user = Auth::user();

        if (!$user->hasPermission($permission)) {
            return response()->json([
                'message' => 'Unauthorized - permission denied',
            ], 403);
        }

        return $next($request);
    }
}
