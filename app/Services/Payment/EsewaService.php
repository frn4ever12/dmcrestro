<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EsewaService implements PaymentGatewayInterface
{
    private string $merchantCode;
    private bool $testMode;
    private string $apiUrl;
    private string $verifyUrl;

    public function __construct()
    {
        $this->merchantCode = config('payment.esewa.merchant_code');
        $this->testMode = config('payment.esewa.test_mode', true);
        $this->apiUrl = $this->testMode 
            ? 'https://uat.esewa.com.np/epay/main' 
            : 'https://esewa.com.np/epay/main';
        $this->verifyUrl = $this->testMode
            ? 'https://uat.esewa.com.np/epay/transrec'
            : 'https://esewa.com.np/epay/transrec';
    }

    public function initiatePayment(array $data): array
    {
        $amount = $data['amount'];
        $orderId = $data['order_id'];
        $successUrl = $data['success_url'];
        $failureUrl = $data['failure_url'];
        $serviceCharge = config('payment.esewa.service_charge', 0);

        $totalAmount = $amount + $serviceCharge;

        $paymentData = [
            'amt' => $totalAmount,
            'psc' => 0,
            'pdc' => 0,
            'txAmt' => 0,
            'tAmt' => $totalAmount,
            'scd' => $this->merchantCode,
            'pid' => $orderId,
            'su' => $successUrl,
            'fu' => $failureUrl,
        ];

        return [
            'success' => true,
            'payment_url' => $this->apiUrl,
            'method' => 'POST',
            'data' => $paymentData,
            'total_amount' => $totalAmount,
        ];
    }

    public function verifyPayment(string $transactionId): array
    {
        $response = Http::asForm()->post($this->verifyUrl, [
            'scd' => $this->merchantCode,
            'rid' => $transactionId,
            'pid' => request()->oid,
            'amt' => request()->amt,
        ]);

        if ($response->successful() && $response->body() === 'Success') {
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'status' => 'completed',
                'amount' => request()->amt,
            ];
        }

        return [
            'success' => false,
            'message' => 'Payment verification failed',
        ];
    }

    public function refundPayment(string $transactionId, float $amount): array
    {
        // eSewa refund requires manual processing through their portal
        Log::info('eSewa refund request', [
            'transaction_id' => $transactionId,
            'amount' => $amount,
        ]);

        return [
            'success' => false,
            'message' => 'eSewa refunds must be processed manually through the merchant portal',
        ];
    }

    public function getPaymentStatus(string $transactionId): string
    {
        $verification = $this->verifyPayment($transactionId);
        return $verification['success'] ? 'completed' : 'failed';
    }
}
