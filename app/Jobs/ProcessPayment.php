<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\Payment\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $gateway;
    protected $transactionId;

    public function __construct(Order $order, string $gateway, string $transactionId)
    {
        $this->order = $order;
        $this->gateway = $gateway;
        $this->transactionId = $transactionId;
    }

    public function handle(PaymentService $paymentService): void
    {
        try {
            $result = $paymentService->verifyPayment($this->gateway, $this->transactionId);

            if ($result['success']) {
                $this->order->update([
                    'payment_status' => 'paid',
                    'paid_amount' => $this->order->total_amount,
                ]);

                Log::info('Payment processed successfully', [
                    'order_id' => $this->order->id,
                    'transaction_id' => $this->transactionId,
                ]);
            } else {
                Log::warning('Payment verification failed', [
                    'order_id' => $this->order->id,
                    'transaction_id' => $this->transactionId,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to process payment', [
                'order_id' => $this->order->id,
                'transaction_id' => $this->transactionId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
