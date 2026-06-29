<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Repositories\MenuItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    private MenuItemRepository $menuItemRepository;

    public function __construct(MenuItemRepository $menuItemRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'restaurant_id', 'branch_id', 'category_id', 
            'is_available', 'is_featured', 'is_vegetarian', 'is_vegan', 'search'
        ]);

        $items = $this->menuItemRepository->paginateWithFilters(
            $filters, 
            $request->per_page ?? 15
        );

        return response()->json([
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'name_np' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:menu_items,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'tax_rate' => 'sometimes|required|numeric|min:0|max:100',
            'discount_percentage' => 'sometimes|required|numeric|min:0|max:100',
            'barcode' => 'nullable|string|max:50|unique:menu_items,barcode',
            'sku' => 'nullable|string|max:50|unique:menu_items,sku',
            'kitchen_section' => 'nullable|string|max:100',
            'preparation_time' => 'nullable|integer|min:1',
            'recipe' => 'nullable|string',
            'ingredients' => 'nullable|array',
            'allergens' => 'nullable|array',
            'calories' => 'nullable|integer|min:0',
            'is_vegetarian' => 'sometimes|required|boolean',
            'is_vegan' => 'sometimes|required|boolean',
            'is_spicy' => 'sometimes|required|boolean',
            'is_available' => 'sometimes|required|boolean',
            'is_featured' => 'sometimes|required|boolean',
            'available_from' => 'nullable|date',
            'available_to' => 'nullable|date',
            'available_days' => 'nullable|array',
            'sort_order' => 'sometimes|required|integer',
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

        $item = MenuItem::create($data);

        // Attach modifiers if provided
        if ($request->has('modifier_ids') && is_array($request->modifier_ids)) {
            $item->modifiers()->attach($request->modifier_ids);
        }

        return response()->json([
            'message' => 'Menu item created successfully',
            'item' => $item->load('category', 'modifiers'),
        ], 201);
    }

    public function show($id)
    {
        $item = MenuItem::with(['category', 'modifiers'])->findOrFail($id);

        return response()->json([
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:menu_categories,id',
            'name' => 'sometimes|required|string|max:255',
            'name_np' => 'nullable|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:menu_items,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'tax_rate' => 'sometimes|required|numeric|min:0|max:100',
            'discount_percentage' => 'sometimes|required|numeric|min:0|max:100',
            'barcode' => 'nullable|string|max:50|unique:menu_items,barcode,' . $id,
            'sku' => 'nullable|string|max:50|unique:menu_items,sku,' . $id,
            'kitchen_section' => 'nullable|string|max:100',
            'preparation_time' => 'nullable|integer|min:1',
            'recipe' => 'nullable|string',
            'ingredients' => 'nullable|array',
            'allergens' => 'nullable|array',
            'calories' => 'nullable|integer|min:0',
            'is_vegetarian' => 'sometimes|required|boolean',
            'is_vegan' => 'sometimes|required|boolean',
            'is_spicy' => 'sometimes|required|boolean',
            'is_available' => 'sometimes|required|boolean',
            'is_featured' => 'sometimes|required|boolean',
            'available_from' => 'nullable|date',
            'available_to' => 'nullable|date',
            'available_days' => 'nullable|array',
            'sort_order' => 'sometimes|required|integer',
            'is_active' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $item = MenuItem::findOrFail($id);
        $item->update($request->all());

        // Sync modifiers if provided
        if ($request->has('modifier_ids')) {
            $item->modifiers()->sync($request->modifier_ids);
        }

        return response()->json([
            'message' => 'Menu item updated successfully',
            'item' => $item->load('category', 'modifiers'),
        ]);
    }

    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);
        
        if ($item->orderItems()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete item with order history',
            ], 422);
        }

        $item->delete();

        return response()->json([
            'message' => 'Menu item deleted successfully',
        ]);
    }

    public function toggleAvailability($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->update(['is_available' => !$item->is_available]);

        return response()->json([
            'message' => 'Item availability toggled successfully',
            'item' => $item,
        ]);
    }

    public function toggleFeatured($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->update(['is_featured' => !$item->is_featured]);

        return response()->json([
            'message' => 'Item featured status toggled successfully',
            'item' => $item,
        ]);
    }
}
