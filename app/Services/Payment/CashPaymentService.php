<?php

namespace App\Services\Payment;

class CashPaymentService implements PaymentGatewayInterface
{
    public function initiatePayment(array $data): array
    {
        return [
            'success' => true,
            'message' => 'Cash payment recorded',
            'status' => 'pending',
        ];
    }

    public function verifyPayment(string $transactionId): array
    {
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'status' => 'completed',
        ];
    }

    public function refundPayment(string $transactionId, float $amount): array
    {
        return [
            'success' => true,
            'message' => 'Cash refund processed',
        ];
    }

    public function getPaymentStatus(string $transactionId): string
    {
        return 'completed';
    }
}
