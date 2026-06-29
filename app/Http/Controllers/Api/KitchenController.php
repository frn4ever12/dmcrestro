<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KitchenOrder;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->branch_id;

        $orders = KitchenOrder::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->has('kitchen_section'), fn($q) => $q->where('kitchen_section', $request->kitchen_section))
            ->with(['order', 'order.table', 'orderItem'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order = KitchenOrder::findOrFail($id);
        $order->update($request->only(['status', 'notes']));

        return response()->json([
            'message' => 'Kitchen order updated successfully',
            'order' => $order,
        ]);
    }

    public function startPreparing($id)
    {
        $order = KitchenOrder::findOrFail($id);
        $order->update([
            'status' => 'preparing',
            'started_at' => now(),
        ]);

        return response()->json([
            'message' => 'Order preparation started',
            'order' => $order,
        ]);
    }

    public function markReady($id)
    {
        $order = KitchenOrder::findOrFail($id);
        $order->update([
            'status' => 'ready',
            'ready_at' => now(),
        ]);

        return response()->json([
            'message' => 'Order marked as ready',
            'order' => $order,
        ]);
    }

    public function markServed($id)
    {
        $order = KitchenOrder::findOrFail($id);
        $order->update([
            'status' => 'served',
            'served_at' => now(),
        ]);

        return response()->json([
            'message' => 'Order marked as served',
            'order' => $order,
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->branch_id;

        $pending = KitchenOrder::where('branch_id', $branchId)
            ->where('status', 'pending')
            ->count();

        $preparing = KitchenOrder::where('branch_id', $branchId)
            ->where('status', 'preparing')
            ->count();

        $ready = KitchenOrder::where('branch_id', $branchId)
            ->where('status', 'ready')
            ->count();

        $served = KitchenOrder::where('branch_id', $branchId)
            ->where('status', 'served')
            ->whereDate('served_at', today())
            ->count();

        return response()->json([
            'pending_orders' => $pending,
            'preparing_orders' => $preparing,
            'ready_orders' => $ready,
            'served_today' => $served,
        ]);
    }
}
