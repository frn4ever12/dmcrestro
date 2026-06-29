<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KhaltiService implements PaymentGatewayInterface
{
    private string $publicKey;
    private string $secretKey;
    private bool $testMode;
    private string $apiUrl;

    public function __construct()
    {
        $this->publicKey = config('payment.khalti.public_key');
        $this->secretKey = config('payment.khalti.secret_key');
        $this->testMode = config('payment.khalti.test_mode', true);
        $this->apiUrl = $this->testMode
            ? 'https://khalti.com/api/v2'
            : 'https://khalti.com/api/v2';
    }

    public function initiatePayment(array $data): array
    {
        $amount = $data['amount'] * 100; // Khalti uses paisa (1 NPR = 100 paisa)
        $orderId = $data['order_id'];
        $productName = $data['product_name'] ?? 'Restaurant Order';
        $successUrl = $data['success_url'];
        $failureUrl = $data['failure_url'];

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $this->publicKey,
        ])->post($this->apiUrl . '/epayment/initiate/', [
            'amount' => $amount,
            'identity' => $orderId,
            'product_url' => request()->getHttpHost(),
            'product_name' => $productName,
            'product_delivery_charge' => 0,
            'product_service_charge' => 0,
            'total_amount' => $amount,
            'success_url' => $successUrl,
            'failure_url' => $failureUrl,
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'payment_url' => $response->json('payment_url'),
                'pidx' => $response->json('pidx'),
                'amount' => $amount / 100,
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to initiate Khalti payment',
            'error' => $response->json(),
        ];
    }

    public function verifyPayment(string $pidx): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $this->secretKey,
        ])->post($this->apiUrl . '/epayment/lookup/', [
            'pidx' => $pidx,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            if ($data['status'] === 'Completed') {
                return [
                    'success' => true,
                    'pidx' => $pidx,
                    'transaction_id' => $data['transaction_id'] ?? $pidx,
                    'status' => 'completed',
                    'amount' => $data['amount'] / 100,
                    'data' => $data,
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Payment verification failed',
        ];
    }

    public function refundPayment(string $transactionId, float $amount): array
    {
        $amountInPaisa = $amount * 100;

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $this->secretKey,
        ])->post($this->apiUrl . '/epayment/refund/', [
            'transaction_id' => $transactionId,
            'amount' => $amountInPaisa,
            'reason' => 'Customer request',
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Refund initiated successfully',
            ];
        }

        return [
            'success' => false,
            'message' => 'Refund failed',
            'error' => $response->json(),
        ];
    }

    public function getPaymentStatus(string $pidx): string
    {
        $verification = $this->verifyPayment($pidx);
        return $verification['success'] ? 'completed' : 'failed';
    }
}
