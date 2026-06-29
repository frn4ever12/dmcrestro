<?php

namespace App\Http\Controllers\Api\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $purchases = Purchase::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->has('supplier_id'), fn($q) => $q->where('supplier_id', $request->supplier_id))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->with(['supplier', 'items'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'purchases' => $purchases,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'required|string|max:100',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        return DB::transaction(function () use ($request) {
            $user = auth()->user();
            $data = array_merge($request->all(), [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'tenant_id' => $user->tenant_id,
                'restaurant_id' => $user->restaurant_id,
                'branch_id' => $user->branch_id,
                'status' => 'received',
            ]);

            $purchase = Purchase::create($data);

            // Add purchase items
            foreach ($request->items as $item) {
                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'inventory_item_id' => $item['inventory_item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'total_price' => ($item['quantity'] * $item['unit_price']) - ($item['discount_amount'] ?? 0),
                ]);

                // Update inventory stock
                $inventoryItem = \App\Models\InventoryItem::find($item['inventory_item_id']);
                if ($inventoryItem) {
                    $inventoryItem->increment('current_stock', $item['quantity']);
                }
            }

            return response()->json([
                'message' => 'Purchase created successfully',
                'purchase' => $purchase->load(['supplier', 'items']),
            ], 201);
        });
    }

    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'items.inventoryItem'])->findOrFail($id);

        return response()->json([
            'purchase' => $purchase,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'sometimes|required|exists:suppliers,id',
            'invoice_number' => 'sometimes|required|string|max:100',
            'purchase_date' => 'sometimes|required|date',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'sometimes|required|in:pending,received,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $purchase = Purchase::findOrFail($id);
        $purchase->update($request->all());

        return response()->json([
            'message' => 'Purchase updated successfully',
            'purchase' => $purchase->load(['supplier', 'items']),
        ]);
    }
}
