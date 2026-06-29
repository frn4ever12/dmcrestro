<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private OrderRepository $orderRepository;
    private OrderService $orderService;

    public function __construct(OrderRepository $orderRepository, OrderService $orderService)
    {
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'status', 'payment_status', 'branch_id', 
            'restaurant_id', 'tenant_id', 'order_type',
            'from_date', 'to_date', 'search'
        ]);

        $orders = $this->orderRepository->paginateWithFilters(
            $filters, 
            $request->per_page ?? 15
        );

        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'branch_id' => 'required|exists:branches,id',
            'customer_id' => 'nullable|exists:users,id',
            'table_id' => 'nullable|exists:tables,id',
            'waiter_id' => 'nullable|exists:users,id',
            'cashier_id' => 'nullable|exists:users,id',
            'order_type' => 'sometimes|required|in:dine_in,takeaway,delivery',
            'order_source' => 'sometimes|required|in:pos,online,qr',
            'delivery_charge' => 'sometimes|required|numeric|min:0',
            'notes' => 'nullable|string',
            'customer_notes' => 'nullable|string',
            'scheduled_for' => 'nullable|date',
            'delivery_address' => 'nullable|string',
            'delivery_phone' => 'nullable|string',
            'is_complimentary' => 'sometimes|required|boolean',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.modifiers' => 'nullable|array',
            'items.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = $this->orderService->createOrder($request->all());

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load(['customer', 'table', 'waiter', 'cashier', 'items']),
        ], 201);
    }

    public function show($id)
    {
        $order = $this->orderRepository->findOrFail($id);
        $order->load(['customer', 'table', 'waiter', 'cashier', 'items', 'payments']);

        return response()->json([
            'order' => $order,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:users,id',
            'table_id' => 'nullable|exists:tables,id',
            'waiter_id' => 'nullable|exists:users,id',
            'cashier_id' => 'nullable|exists:users,id',
            'order_type' => 'sometimes|required|in:dine_in,takeaway,delivery',
            'notes' => 'nullable|string',
            'customer_notes' => 'nullable|string',
            'delivery_address' => 'nullable|string',
            'delivery_phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = $this->orderService->updateOrder($id, $request->all());

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order,
        ]);
    }

    public function destroy($id)
    {
        $this->orderRepository->delete($id);

        return response()->json([
            'message' => 'Order deleted successfully',
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $order = $this->orderService->cancelOrder($id, $request->reason);

        return response()->json([
            'message' => 'Order cancelled successfully',
            'order' => $order,
        ]);
    }

    public function complete($id)
    {
        $order = $this->orderService->completeOrder($id);

        return response()->json([
            'message' => 'Order completed successfully',
            'order' => $order,
        ]);
    }

    public function addPayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:cash,card,esewa,khalti,fonepay,connectips,qr',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'nullable|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = $this->orderService->addPayment($id, $request->all());

        return response()->json([
            'message' => 'Payment added successfully',
            'order' => $order->load('payments'),
        ]);
    }

    public function dashboard(Request $request)
    {
        $branchId = $request->user()->branch_id;
        $data = $this->orderService->getDashboardData($branchId);

        return response()->json($data);
    }
}
