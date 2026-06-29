<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonepayService implements PaymentGatewayInterface
{
    private string $merchantId;
    private string $secretKey;
    private bool $testMode;
    private string $apiUrl;

    public function __construct()
    {
        $this->merchantId = config('payment.fonepay.merchant_id');
        $this->secretKey = config('payment.fonepay.secret_key');
        $this->testMode = config('payment.fonepay.test_mode', true);
        $this->apiUrl = $this->testMode
            ? 'https://uat.fonepay.com/api'
            : 'https://fonepay.com/api';
    }

    public function initiatePayment(array $data): array
    {
        $amount = $data['amount'];
        $orderId = $data['order_id'];
        $successUrl = $data['success_url'];
        $failureUrl = $data['failure_url'];

        // Generate signature
        $signature = $this->generateSignature([
            'MERCHANT_ID' => $this->merchantId,
            'APP_ID' => config('payment.fonepay.app_id'),
            'APP_NAME' => config('app.name'),
            'AMOUNT' => $amount,
            'REF_ID' => $orderId,
        ]);

        $paymentData = [
            'MERCHANT_ID' => $this->merchantId,
            'APP_ID' => config('payment.fonepay.app_id'),
            'APP_NAME' => config('app.name'),
            'AMOUNT' => $amount,
            'REF_ID' => $orderId,
            'SUCCESS_URL' => $successUrl,
            'FAILURE_URL' => $failureUrl,
            'SIGNATURE' => $signature,
        ];

        return [
            'success' => true,
            'payment_url' => $this->apiUrl . '/payment',
            'method' => 'POST',
            'data' => $paymentData,
        ];
    }

    public function verifyPayment(string $transactionId): array
    {
        $response = Http::post($this->apiUrl . '/verify', [
            'MERCHANT_ID' => $this->merchantId,
            'REF_ID' => request()->ref_id,
            'AMOUNT' => request()->amount,
            'SIGNATURE' => request()->signature,
        ]);

        if ($response->successful() && $response->json('status') === 'success') {
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'status' => 'completed',
                'amount' => request()->amount,
            ];
        }

        return [
            'success' => false,
            'message' => 'Payment verification failed',
        ];
    }

    public function refundPayment(string $transactionId, float $amount): array
    {
        // FonePay refund requires API integration
        Log::info('FonePay refund request', [
            'transaction_id' => $transactionId,
            'amount' => $amount,
        ]);

        return [
            'success' => false,
            'message' => 'FonePay refund requires manual processing',
        ];
    }

    public function getPaymentStatus(string $transactionId): string
    {
        $verification = $this->verifyPayment($transactionId);
        return $verification['success'] ? 'completed' : 'failed';
    }

    private function generateSignature(array $data): string
    {
        ksort($data);
        $signatureString = implode(':', array_values($data));
        return hash_hmac('sha512', $signatureString, $this->secretKey);
    }
}
