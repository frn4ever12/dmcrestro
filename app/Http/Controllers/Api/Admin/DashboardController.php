<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Order;
use App\Repositories\TenantRepository;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private TenantRepository $tenantRepository;

    public function __construct(TenantRepository $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }

    public function index()
    {
        // Tenant Statistics
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'active')->count();
        $trialTenants = Tenant::where('status', 'trial')->count();
        $suspendedTenants = Tenant::where('status', 'suspended')->count();
        $expiredTenants = Tenant::where('subscription_ends_at', '<', now())->count();

        // Revenue Statistics
        $activeTenantsWithPlans = Tenant::where('status', 'active')
            ->with('subscriptionPlan')
            ->get();
        
        $mrr = $activeTenantsWithPlans->sum(function ($tenant) {
            return $tenant->subscriptionPlan->monthly_price ?? 0;
        });

        $arr = $mrr * 12;

        // User Statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();

        // Growth Statistics
        $newTenantsThisMonth = Tenant::where('created_at', '>=', now()->startOfMonth())->count();
        $newTenantsLastMonth = Tenant::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ])->count();

        // Subscription Plan Distribution
        $planDistribution = SubscriptionPlan::withCount('tenants')
            ->active()
            ->get()
            ->map(function ($plan) {
                return [
                    'name' => $plan->name,
                    'tenants_count' => $plan->tenants_count,
                ];
            });

        // Recent Activity
        $recentTenants = Tenant::latest()->take(5)->get();
        $recentExpiring = Tenant::where('subscription_ends_at', '>', now())
            ->where('subscription_ends_at', '<', now()->addDays(7))
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'tenant_statistics' => [
                'total' => $totalTenants,
                'active' => $activeTenants,
                'trial' => $trialTenants,
                'suspended' => $suspendedTenants,
                'expired' => $expiredTenants,
            ],
            'revenue_statistics' => [
                'mrr' => $mrr,
                'arr' => $arr,
            ],
            'user_statistics' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
            ],
            'growth_statistics' => [
                'new_this_month' => $newTenantsThisMonth,
                'new_last_month' => $newTenantsLastMonth,
                'growth_rate' => $newTenantsLastMonth > 0 
                    ? (($newTenantsThisMonth - $newTenantsLastMonth) / $newTenantsLastMonth) * 100 
                    : 0,
            ],
            'plan_distribution' => $planDistribution,
            'recent_tenants' => $recentTenants,
            'recently_expiring' => $recentExpiring,
        ]);
    }

    public function revenue()
    {
        $revenueByMonth = Tenant::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyRevenue = [];
        foreach ($revenueByMonth as $data) {
            $monthlyRevenue[] = [
                'month' => $data->month,
                'count' => $data->count,
            ];
        }

        return response()->json([
            'monthly_revenue' => $monthlyRevenue,
        ]);
    }

    public function tenants()
    {
        $tenants = Tenant::with(['subscriptionPlan', 'restaurants'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'tenants' => $tenants,
        ]);
    }
}
