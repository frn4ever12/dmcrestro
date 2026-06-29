<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConnectIPSService implements PaymentGatewayInterface
{
    private string $merchantId;
    private string $secretKey;
    private bool $testMode;
    private string $apiUrl;

    public function __construct()
    {
        $this->merchantId = config('payment.connectips.merchant_id');
        $this->secretKey = config('payment.connectips.secret_key');
        $this->testMode = config('payment.connectips.test_mode', true);
        $this->apiUrl = $this->testMode
            ? 'https://uat.connectips.com/api'
            : 'https://connectips.com/api';
    }

    public function initiatePayment(array $data): array
    {
        $amount = $data['amount'];
        $orderId = $data['order_id'];
        $successUrl = $data['success_url'];
        $failureUrl = $data['failure_url'];

        // ConnectIPS requires customer authentication
        $paymentData = [
            'MERCHANT_ID' => $this->merchantId,
            'APP_ID' => config('payment.connectips.app_id'),
            'REFERENCE_ID' => $orderId,
            'AMOUNT' => $amount,
            'SUCCESS_URL' => $successUrl,
            'FAILURE_URL' => $failureUrl,
            'REMARKS' => 'Restaurant Order Payment',
        ];

        return [
            'success' => true,
            'payment_url' => $this->apiUrl . '/payment/initiate',
            'method' => 'POST',
            'data' => $paymentData,
        ];
    }

    public function verifyPayment(string $transactionId): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
        ])->get($this->apiUrl . '/payment/verify/' . $transactionId);

        if ($response->successful() && $response->json('status') === 'SUCCESS') {
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'status' => 'completed',
                'amount' => $response->json('amount'),
            ];
        }

        return [
            'success' => false,
            'message' => 'Payment verification failed',
        ];
    }

    public function refundPayment(string $transactionId, float $amount): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
        ])->post($this->apiUrl . '/payment/refund', [
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'reason' => 'Refund request',
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
        ];
    }

    public function getPaymentStatus(string $transactionId): string
    {
        $verification = $this->verifyPayment($transactionId);
        return $verification['success'] ? 'completed' : 'failed';
    }
}
