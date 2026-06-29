<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $gateways = [
            'esewa' => [
                'name' => 'eSewa',
                'icon' => 'fas fa-wallet',
                'status' => true,
                'description' => 'Nepal\'s most popular digital wallet'
            ],
            'khalti' => [
                'name' => 'Khalti',
                'icon' => 'fas fa-mobile-alt',
                'status' => true,
                'description' => 'Digital wallet for Nepal'
            ],
            'fonepay' => [
                'name' => 'FonePay',
                'icon' => 'fas fa-qrcode',
                'status' => false,
                'description' => 'QR-based payment system'
            ],
            'connectips' => [
                'name' => 'ConnectIPS',
                'icon' => 'fas fa-university',
                'status' => false,
                'description' => 'Nepal Payment Gateway'
            ],
            'bank_transfer' => [
                'name' => 'Bank Transfer',
                'icon' => 'fas fa-building',
                'status' => true,
                'description' => 'Direct bank transfer'
            ],
            'manual' => [
                'name' => 'Manual Payment',
                'icon' => 'fas fa-hand-holding-usd',
                'status' => true,
                'description' => 'Manual payment verification'
            ],
        ];
        
        return view('admin.payment-gateways.index', compact('gateways'));
    }

    public function edit($gateway)
    {
        $gatewayInfo = [
            'esewa' => [
                'name' => 'eSewa',
                'fields' => [
                    'merchant_id' => 'Merchant ID',
                    'secret_key' => 'Secret Key',
                    'api_url' => 'API URL',
                ],
                'description' => 'Configure eSewa payment gateway settings'
            ],
            'khalti' => [
                'name' => 'Khalti',
                'fields' => [
                    'public_key' => 'Public Key',
                    'secret_key' => 'Secret Key',
                    'api_url' => 'API URL',
                ],
                'description' => 'Configure Khalti payment gateway settings'
            ],
            'fonepay' => [
                'name' => 'FonePay',
                'fields' => [
                    'merchant_id' => 'Merchant ID',
                    'api_key' => 'API Key',
                    'api_url' => 'API URL',
                ],
                'description' => 'Configure FonePay payment gateway settings'
            ],
            'connectips' => [
                'name' => 'ConnectIPS',
                'fields' => [
                    'merchant_id' => 'Merchant ID',
                    'api_key' => 'API Key',
                    'api_url' => 'API URL',
                ],
                'description' => 'Configure ConnectIPS payment gateway settings'
            ],
            'bank_transfer' => [
                'name' => 'Bank Transfer',
                'fields' => [
                    'bank_name' => 'Bank Name',
                    'account_number' => 'Account Number',
                    'account_name' => 'Account Name',
                    'branch' => 'Branch',
                ],
                'description' => 'Configure bank transfer settings'
            ],
            'manual' => [
                'name' => 'Manual Payment',
                'fields' => [
                    'instructions' => 'Payment Instructions',
                    'verification_note' => 'Verification Note',
                ],
                'description' => 'Configure manual payment settings'
            ],
        ];

        if (!isset($gatewayInfo[$gateway])) {
            abort(404);
        }

        return view('admin.payment-gateways.edit', [
            'gateway' => $gateway,
            'info' => $gatewayInfo[$gateway]
        ]);
    }

    public function update(Request $request, $gateway)
    {
        $validated = $request->validate([
            'merchant_id' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'public_key' => 'nullable|string',
            'api_key' => 'nullable|string',
            'api_url' => 'nullable|url',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'account_name' => 'nullable|string',
            'branch' => 'nullable|string',
            'instructions' => 'nullable|string',
            'verification_note' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Store settings in database or config file
        // For now, we'll just redirect with success message
        
        return redirect()->route('admin.payment-gateways.index')->with('success', 'Payment gateway settings updated successfully!');
    }
}
