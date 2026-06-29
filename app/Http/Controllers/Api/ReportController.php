<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $startDate = $request->from_date ?? now()->startOfMonth();
        $endDate = $request->to_date ?? now()->endOfDay();

        $orders = Order::whereBetween('order_date', [$startDate, $endDate])
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'completed')
            ->get();

        $totalSales = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        $salesByPaymentMethod = $orders->flatMap(function ($order) {
            return $order->payments->map(function ($payment) {
                return [
                    'payment_method' => $payment->payment_method,
                    'amount' => $payment->amount,
                ];
            });
        })->groupBy('payment_method')->map(function ($payments) {
            return $payments->sum('amount');
        });

        return response()->json([
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'sales_by_payment_method' => $salesByPaymentMethod,
            'period' => [
                'from' => $startDate,
                'to' => $endDate,
            ],
        ]);
    }

    public function purchaseReport(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $startDate = $request->from_date ?? now()->startOfMonth();
        $endDate = $request->to_date ?? now()->endOfDay();

        $purchases = Purchase::whereBetween('purchase_date', [$startDate, $endDate])
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'received')
            ->get();

        $totalPurchases = $purchases->sum('total_amount');
        $totalPurchasesCount = $purchases->count();

        return response()->json([
            'total_purchases' => $totalPurchases,
            'total_purchase_count' => $totalPurchasesCount,
            'period' => [
                'from' => $startDate,
                'to' => $endDate,
            ],
        ]);
    }

    public function inventoryReport(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $totalItems = InventoryItem::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();

        $totalStockValue = InventoryItem::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->selectRaw('SUM(current_stock * cost_price) as total_value')
            ->first()
            ->total_value ?? 0;

        $lowStockItems = InventoryItem::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('current_stock', '<=', DB::raw('reorder_level'))
            ->count();

        return response()->json([
            'total_items' => $totalItems,
            'total_stock_value' => $totalStockValue,
            'low_stock_items' => $lowStockItems,
        ]);
    }

    public function profitLossReport(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $startDate = $request->from_date ?? now()->startOfMonth();
        $endDate = $request->to_date ?? now()->endOfDay();

        // Sales Revenue
        $salesRevenue = Order::whereBetween('order_date', [$startDate, $endDate])
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'completed')
            ->sum('total_amount');

        // Cost of Goods Sold (simplified - using cost price from menu items)
        $cogs = Order::whereBetween('order_date', [$startDate, $endDate])
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'completed')
            ->get()
            ->flatMap(function ($order) {
                return $order->items->map(function ($item) {
                    $menuItem = \App\Models\MenuItem::find($item->menu_item_id);
                    return $menuItem ? ($menuItem->cost_price * $item->quantity) : 0;
                });
            })
            ->sum();

        // Purchase Cost
        $purchaseCost = Purchase::whereBetween('purchase_date', [$startDate, $endDate])
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'received')
            ->sum('total_amount');

        $grossProfit = $salesRevenue - $cogs;
        $netProfit = $grossProfit - $purchaseCost;

        return response()->json([
            'sales_revenue' => $salesRevenue,
            'cost_of_goods_sold' => $cogs,
            'purchase_cost' => $purchaseCost,
            'gross_profit' => $grossProfit,
            'net_profit' => $netProfit,
            'profit_margin' => $salesRevenue > 0 ? ($netProfit / $salesRevenue) * 100 : 0,
            'period' => [
                'from' => $startDate,
                'to' => $endDate,
            ],
        ]);
    }

    public function dailyReport(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->branch_id;

        $date = $request->date ?? today();

        $orders = Order::whereDate('order_date', $date)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'completed')
            ->get();

        $totalSales = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        return response()->json([
            'date' => $date,
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'orders' => $orders,
        ]);
    }

    public function monthlyReport(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->branch_id;

        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        $startDate = now()->setYear($year)->setMonth($month)->startOfMonth();
        $endDate = now()->setYear($year)->setMonth($month)->endOfMonth();

        $orders = Order::whereBetween('order_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'completed')
            ->get();

        $totalSales = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        // Daily breakdown
        $dailySales = Order::whereBetween('order_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'completed')
            ->selectRaw('DATE(order_date) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'year' => $year,
            'month' => $month,
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'daily_sales' => $dailySales,
        ]);
    }
}
