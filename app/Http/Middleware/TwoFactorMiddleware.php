<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->two_factor_enabled) {
            // Check if 2FA is verified
            if (!$request->session()->get('2fa_verified')) {
                return response()->json([
                    'message' => '2FA verification required',
                    'requires_2fa' => true,
                ], 403);
            }
        }

        return $next($request);
    }
}
