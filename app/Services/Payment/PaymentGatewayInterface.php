<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    public function initiatePayment(array $data): array;
    public function verifyPayment(string $transactionId): array;
    public function refundPayment(string $transactionId, float $amount): array;
    public function getPaymentStatus(string $transactionId): string;
}
