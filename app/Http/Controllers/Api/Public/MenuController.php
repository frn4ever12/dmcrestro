<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function show($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        
        $categories = MenuCategory::where('restaurant_id', $restaurantId)
            ->active()
            ->available()
            ->ordered()
            ->with(['menuItems' => function($query) {
                $query->available()->active()->orderBy('sort_order');
            }])
            ->get();

        return response()->json([
            'restaurant' => $restaurant,
            'categories' => $categories,
        ]);
    }

    public function showByQR($restaurantId, $tableId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $table = Table::findOrFail($tableId);

        $categories = MenuCategory::where('restaurant_id', $restaurantId)
            ->active()
            ->available()
            ->ordered()
            ->with(['menuItems' => function($query) {
                $query->available()->active()->orderBy('sort_order');
            }])
            ->get();

        return response()->json([
            'restaurant' => $restaurant,
            'table' => $table,
            'categories' => $categories,
        ]);
    }

    public function search(Request $request, $restaurantId)
    {
        $query = $request->get('q', '');

        $items = MenuItem::where('restaurant_id', $restaurantId)
            ->available()
            ->active()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('name_np', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('category')
            ->get();

        return response()->json([
            'items' => $items,
        ]);
    }
}
