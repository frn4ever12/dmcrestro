<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Table;
use App\Models\InventoryItem;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        // Today's Statistics
        $todaySales = $this->orderRepository->getTodaySales($branchId);
        $todayOrders = $this->orderRepository->getTodayOrderCount($branchId);
        $pendingOrders = $this->orderRepository->getPendingOrders()
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();
        $completedOrders = $this->orderRepository->getCompletedOrders()
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();

        // Menu Statistics
        $totalMenuItems = MenuItem::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();
        $availableItems = MenuItem::available()
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();

        // Table Statistics
        $totalTables = Table::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();
        $occupiedTables = Table::occupied()
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();
        $availableTables = Table::available()
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();

        // Inventory Statistics
        $lowStockItems = InventoryItem::where('current_stock', '<=', DB::raw('reorder_level'))
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();

        // Recent Orders
        $recentOrders = Order::with(['customer', 'table'])
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->latest()
            ->take(10)
            ->get();

        // Top Selling Items
        $topSellingItems = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->when($restaurantId, fn($q) => $q->where('orders.restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereDate('orders.order_date', '>=', now()->subDays(30))
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'today_statistics' => [
                'sales' => $todaySales,
                'orders' => $todayOrders,
                'pending_orders' => $pendingOrders,
                'completed_orders' => $completedOrders,
            ],
            'menu_statistics' => [
                'total_items' => $totalMenuItems,
                'available_items' => $availableItems,
            ],
            'table_statistics' => [
                'total_tables' => $totalTables,
                'occupied_tables' => $occupiedTables,
                'available_tables' => $availableTables,
            ],
            'inventory_statistics' => [
                'low_stock_items' => $lowStockItems,
            ],
            'recent_orders' => $recentOrders,
            'top_selling_items' => $topSellingItems,
        ]);
    }
}
