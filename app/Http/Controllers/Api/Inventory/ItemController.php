<?php

namespace App\Http\Controllers\Api\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $items = InventoryItem::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->has('category_id'), fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->has('search'), fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->with(['category', 'unit', 'supplier'])
            ->get();

        return response()->json([
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:inventory_categories,id',
            'unit_id' => 'required|exists:inventory_units,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:inventory_items,sku',
            'barcode' => 'nullable|string|max:50|unique:inventory_items,barcode',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'current_stock' => 'sometimes|required|numeric|min:0',
            'reorder_level' => 'sometimes|required|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'is_active' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();
        $data = array_merge($request->all(), [
            'uuid' => \Illuminate\Support\Str::uuid(),
            'tenant_id' => $user->tenant_id,
            'restaurant_id' => $user->restaurant_id,
            'branch_id' => $user->branch_id,
        ]);

        $item = InventoryItem::create($data);

        return response()->json([
            'message' => 'Inventory item created successfully',
            'item' => $item->load(['category', 'unit', 'supplier']),
        ], 201);
    }

    public function show($id)
    {
        $item = InventoryItem::with(['category', 'unit', 'supplier', 'purchases'])->findOrFail($id);

        return response()->json([
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:inventory_categories,id',
            'unit_id' => 'sometimes|required|exists:inventory_units,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'sometimes|required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:inventory_items,sku,' . $id,
            'barcode' => 'nullable|string|max:50|unique:inventory_items,barcode,' . $id,
            'description' => 'nullable|string',
            'cost_price' => 'sometimes|required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'current_stock' => 'sometimes|required|numeric|min:0',
            'reorder_level' => 'sometimes|required|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'is_active' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $item = InventoryItem::findOrFail($id);
        $item->update($request->all());

        return response()->json([
            'message' => 'Inventory item updated successfully',
            'item' => $item->load(['category', 'unit', 'supplier']),
        ]);
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'Inventory item deleted successfully',
        ]);
    }

    public function lowStock(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $items = InventoryItem::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('current_stock', '<=', \Illuminate\Support\Facades\DB::raw('reorder_level'))
            ->with(['category', 'unit', 'supplier'])
            ->get();

        return response()->json([
            'items' => $items,
        ]);
    }
}
