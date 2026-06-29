<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GenerateDailyReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $restaurantId;
    protected $branchId;
    protected $date;

    public function __construct($restaurantId, $branchId = null, $date = null)
    {
        $this->restaurantId = $restaurantId;
        $this->branchId = $branchId;
        $this->date = $date ?? now()->toDateString();
    }

    public function handle(): void
    {
        try {
            $query = Order::whereDate('order_date', $this->date)
                ->where('status', 'completed');

            if ($this->restaurantId) {
                $query->where('restaurant_id', $this->restaurantId);
            }

            if ($this->branchId) {
                $query->where('branch_id', $this->branchId);
            }

            $orders = $query->get();

            $report = [
                'date' => $this->date,
                'restaurant_id' => $this->restaurantId,
                'branch_id' => $this->branchId,
                'total_orders' => $orders->count(),
                'total_sales' => $orders->sum('total_amount'),
                'total_discount' => $orders->sum('discount_amount'),
                'total_tax' => $orders->sum('tax_amount'),
                'total_service_charge' => $orders->sum('service_charge_amount'),
                'payment_methods' => $this->getPaymentMethodBreakdown($orders),
            ];

            // Store report in database or send to analytics service
            Log::info('Daily report generated', $report);

        } catch (\Exception $e) {
            Log::error('Failed to generate daily report', [
                'restaurant_id' => $this->restaurantId,
                'branch_id' => $this->branchId,
                'date' => $this->date,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function getPaymentMethodBreakdown($orders)
    {
        return $orders->flatMap(function ($order) {
            return $order->payments->map(function ($payment) {
                return [
                    'method' => $payment->payment_method,
                    'amount' => $payment->amount,
                ];
            });
        })->groupBy('method')->map(function ($payments) {
            return $payments->sum('amount');
        });
    }
}
