<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService extends BaseService
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        parent::__construct($orderRepository);
        $this->orderRepository = $orderRepository;
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $restaurant = \App\Models\Restaurant::findOrFail($data['restaurant_id']);
            
            $order = $this->orderRepository->create([
                'uuid' => Str::uuid(),
                'invoice_number' => $restaurant->generateInvoiceNumber(),
                'tenant_id' => $data['tenant_id'],
                'restaurant_id' => $data['restaurant_id'],
                'branch_id' => $data['branch_id'],
                'customer_id' => $data['customer_id'] ?? null,
                'table_id' => $data['table_id'] ?? null,
                'waiter_id' => $data['waiter_id'] ?? null,
                'cashier_id' => $data['cashier_id'] ?? null,
                'order_type' => $data['order_type'] ?? 'dine_in',
                'order_source' => $data['order_source'] ?? 'pos',
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'subtotal' => 0,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'service_charge_amount' => 0,
                'delivery_charge' => $data['delivery_charge'] ?? 0,
                'total_amount' => 0,
                'paid_amount' => 0,
                'due_amount' => 0,
                'notes' => $data['notes'] ?? null,
                'customer_notes' => $data['customer_notes'] ?? null,
                'order_date' => now(),
                'scheduled_for' => $data['scheduled_for'] ?? null,
                'delivery_address' => $data['delivery_address'] ?? null,
                'delivery_phone' => $data['delivery_phone'] ?? null,
                'is_complimentary' => $data['is_complimentary'] ?? false,
            ]);

            // Add order items
            if (isset($data['items']) && is_array($data['items'])) {
                $subtotal = 0;
                foreach ($data['items'] as $item) {
                    $menuItem = \App\Models\MenuItem::findOrFail($item['menu_item_id']);
                    $itemTotal = $menuItem->price * $item['quantity'];
                    $subtotal += $itemTotal;

                    $order->items()->create([
                        'menu_item_id' => $item['menu_item_id'],
                        'item_name' => $menuItem->name,
                        'unit_price' => $menuItem->price,
                        'quantity' => $item['quantity'],
                        'discount_amount' => 0,
                        'tax_amount' => 0,
                        'total_price' => $itemTotal,
                        'modifiers' => $item['modifiers'] ?? null,
                        'notes' => $item['notes'] ?? null,
                        'status' => 'pending',
                    ]);
                }

                // Calculate totals
                $taxAmount = $subtotal * ($restaurant->tax_rate / 100);
                $serviceChargeAmount = $subtotal * ($restaurant->service_charge_rate / 100);
                $totalAmount = $subtotal + $taxAmount + $serviceChargeAmount + $order->delivery_charge;

                $order->update([
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'service_charge_amount' => $serviceChargeAmount,
                    'total_amount' => $totalAmount,
                    'due_amount' => $totalAmount,
                ]);
            }

            // Update table status if dine-in
            if ($order->order_type === 'dine_in' && $order->table_id) {
                $table = \App\Models\Table::find($order->table_id);
                if ($table) {
                    $table->markAsOccupied();
                }
            }

            // Create kitchen orders
            $this->createKitchenOrders($order);

            return $order->fresh();
        });
    }

    public function updateOrder(int $orderId, array $data): Order
    {
        return DB::transaction(function () use ($orderId, $data) {
            $order = $this->orderRepository->findOrFail($orderId);

            if ($order->status === 'completed' || $order->status === 'cancelled') {
                throw new \Exception('Cannot update completed or cancelled orders');
            }

            $order->update($data);

            return $order->fresh();
        });
    }

    public function addPayment(int $orderId, array $paymentData): Order
    {
        return DB::transaction(function () use ($orderId, $paymentData) {
            $order = $this->orderRepository->findOrFail($orderId);

            $order->payments()->create([
                'payment_method' => $paymentData['payment_method'],
                'amount' => $paymentData['amount'],
                'transaction_id' => $paymentData['transaction_id'] ?? null,
                'reference' => $paymentData['reference'] ?? null,
                'notes' => $paymentData['notes'] ?? null,
                'payment_date' => now(),
            ]);

            $totalPaid = $order->payments()->sum('amount');
            $order->update([
                'paid_amount' => $totalPaid,
                'due_amount' => $order->total_amount - $totalPaid,
                'payment_status' => $totalPaid >= $order->total_amount ? 'paid' : 'partial',
            ]);

            return $order->fresh();
        });
    }

    public function cancelOrder(int $orderId, string $reason): Order
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $order->markAsCancelled($reason);

        // Release table if occupied
        if ($order->table_id) {
            $table = \App\Models\Table::find($order->table_id);
            if ($table && $table->isOccupied()) {
                $table->markAsAvailable();
            }
        }

        return $order->fresh();
    }

    public function completeOrder(int $orderId): Order
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $order->markAsCompleted();

        // Release table if occupied
        if ($order->table_id) {
            $table = \App\Models\Table::find($order->table_id);
            if ($table && $table->isOccupied()) {
                $table->markAsAvailable();
            }
        }

        return $order->fresh();
    }

    protected function createKitchenOrders(Order $order): void
    {
        foreach ($order->items as $item) {
            $menuItem = \App\Models\MenuItem::find($item->menu_item_id);
            if ($menuItem) {
                $order->kitchenOrders()->create([
                    'uuid' => Str::uuid(),
                    'tenant_id' => $order->tenant_id,
                    'restaurant_id' => $order->restaurant_id,
                    'branch_id' => $order->branch_id,
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'table_id' => $order->table_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'modifiers' => $item->modifiers,
                    'notes' => $item->notes,
                    'kitchen_section' => $menuItem->kitchen_section,
                    'preparation_time' => $menuItem->preparation_time,
                    'status' => 'pending',
                ]);
            }
        }
    }

    public function getDashboardData(int $branchId): array
    {
        return [
            'today_sales' => $this->orderRepository->getTodaySales($branchId),
            'today_orders' => $this->orderRepository->getTodayOrderCount($branchId),
            'pending_orders' => $this->orderRepository->getPendingOrders()->where('branch_id', $branchId)->count(),
            'completed_orders' => $this->orderRepository->getCompletedOrders()->where('branch_id', $branchId)->count(),
        ];
    }
}
