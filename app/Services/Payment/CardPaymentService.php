<?php

namespace App\Services\Payment;

class CardPaymentService implements PaymentGatewayInterface
{
    public function initiatePayment(array $data): array
    {
        return [
            'success' => true,
            'message' => 'Card payment recorded',
            'status' => 'pending',
            'card_last_four' => substr($data['card_number'] ?? '', -4),
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
            'message' => 'Card refund processed',
        ];
    }

    public function getPaymentStatus(string $transactionId): string
    {
        return 'completed';
    }
}
