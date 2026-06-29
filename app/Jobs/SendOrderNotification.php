<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendOrderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $type;

    public function __construct(Order $order, string $type = 'new')
    {
        $this->order = $order;
        $this->type = $type;
    }

    public function handle(): void
    {
        try {
            switch ($this->type) {
                case 'new':
                    $this->sendNewOrderNotification();
                    break;
                case 'confirmed':
                    $this->sendConfirmedNotification();
                    break;
                case 'ready':
                    $this->sendReadyNotification();
                    break;
                case 'completed':
                    $this->sendCompletedNotification();
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order notification', [
                'order_id' => $this->order->id,
                'type' => $this->type,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function sendNewOrderNotification(): void
    {
        // Notify kitchen staff
        $kitchenStaff = User::where('restaurant_id', $this->order->restaurant_id)
            ->where('branch_id', $this->order->branch_id)
            ->whereIn('user_type', ['kitchen', 'chef'])
            ->where('is_active', true)
            ->get();

        foreach ($kitchenStaff as $staff) {
            // Send notification (implement notification logic)
            Log::info('New order notification sent', [
                'user_id' => $staff->id,
                'order_id' => $this->order->id,
            ]);
        }
    }

    protected function sendConfirmedNotification(): void
    {
        // Notify waiter
        if ($this->order->waiter_id) {
            $waiter = User::find($this->order->waiter_id);
            Log::info('Order confirmed notification sent', [
                'user_id' => $waiter->id,
                'order_id' => $this->order->id,
            ]);
        }
    }

    protected function sendReadyNotification(): void
    {
        // Notify waiter
        if ($this->order->waiter_id) {
            $waiter = User::find($this->order->waiter_id);
            Log::info('Order ready notification sent', [
                'user_id' => $waiter->id,
                'order_id' => $this->order->id,
            ]);
        }
    }

    protected function sendCompletedNotification(): void
    {
        // Notify customer if online order
        if ($this->order->order_source === 'online' && $this->order->customer_id) {
            $customer = User::find($this->order->customer_id);
            Log::info('Order completed notification sent', [
                'user_id' => $customer->id,
                'order_id' => $this->order->id,
            ]);
        }
    }
}
