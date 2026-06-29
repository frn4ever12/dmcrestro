<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;

class PaymentService
{
    private array $gateways = [
        'esewa' => EsewaService::class,
        'khalti' => KhaltiService::class,
        'fonepay' => FonepayService::class,
        'connectips' => ConnectIPSService::class,
        'cash' => CashPaymentService::class,
        'card' => CardPaymentService::class,
    ];

    public function getGateway(string $gateway): PaymentGatewayInterface
    {
        if (!isset($this->gateways[$gateway])) {
            throw new \InvalidArgumentException("Payment gateway {$gateway} not supported");
        }

        return app($this->gateways[$gateway]);
    }

    public function initiatePayment(string $gateway, array $data): array
    {
        try {
            $paymentGateway = $this->getGateway($gateway);
            return $paymentGateway->initiatePayment($data);
        } catch (\Exception $e) {
            Log::error('Payment initiation failed', [
                'gateway' => $gateway,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to initiate payment',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function verifyPayment(string $gateway, string $transactionId): array
    {
        try {
            $paymentGateway = $this->getGateway($gateway);
            return $paymentGateway->verifyPayment($transactionId);
        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'gateway' => $gateway,
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to verify payment',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function refundPayment(string $gateway, string $transactionId, float $amount): array
    {
        try {
            $paymentGateway = $this->getGateway($gateway);
            return $paymentGateway->refundPayment($transactionId, $amount);
        } catch (\Exception $e) {
            Log::error('Payment refund failed', [
                'gateway' => $gateway,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to process refund',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getPaymentStatus(string $gateway, string $transactionId): string
    {
        try {
            $paymentGateway = $this->getGateway($gateway);
            return $paymentGateway->getPaymentStatus($transactionId);
        } catch (\Exception $e) {
            Log::error('Payment status check failed', [
                'gateway' => $gateway,
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            return 'unknown';
        }
    }

    public function getSupportedGateways(): array
    {
        return array_keys($this->gateways);
    }
}
