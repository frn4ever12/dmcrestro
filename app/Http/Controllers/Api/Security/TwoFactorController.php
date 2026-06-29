<?php

namespace App\Http\Controllers\Api\Security;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TwoFactorController extends Controller
{
    public function enable(Request $request)
    {
        $user = auth()->user();

        // Generate 2FA secret
        $secret = $this->generateSecret();
        
        $user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt($secret),
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        // Generate QR code URL (in production, use a QR code library)
        $qrCodeUrl = $this->generateQrCodeUrl($user->email, $secret);

        return response()->json([
            'message' => '2FA enabled successfully',
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
        ]);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid password',
            ], 422);
        }

        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_expires_at' => null,
        ]);

        return response()->json([
            'message' => '2FA disabled successfully',
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|digits:6',
        ]);

        $user = auth()->user();

        if (!$user->two_factor_enabled) {
            return response()->json([
                'message' => '2FA is not enabled',
            ], 400);
        }

        $secret = decrypt($user->two_factor_secret);

        if ($this->verifyCode($secret, $request->code)) {
            $user->update([
                'two_factor_expires_at' => now()->addDay(),
            ]);

            return response()->json([
                'message' => '2FA verification successful',
            ]);
        }

        return response()->json([
            'message' => 'Invalid 2FA code',
        ], 422);
    }

    protected function generateSecret(): string
    {
        // Generate a random 32-character secret
        return Str::random(32);
    }

    protected function generateQrCodeUrl($email, $secret): string
    {
        // In production, use a proper TOTP library like spatie/2fa
        $appName = config('app.name');
        return "otpauth://totp/{$appName}:{$email}?secret={$secret}&issuer={$appName}";
    }

    protected function verifyCode($secret, $code): bool
    {
        // In production, use a proper TOTP library
        // For now, this is a placeholder
        return true;
    }
}
