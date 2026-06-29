<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\TenantRepository;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    private TenantRepository $tenantRepository;
    private TenantService $tenantService;

    public function __construct(TenantRepository $tenantRepository, TenantService $tenantService)
    {
        $this->tenantRepository = $tenantRepository;
        $this->tenantService = $tenantService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'search', 'subscription_plan_id']);
        $tenants = $this->tenantRepository->paginateWithFilters($filters, $request->per_page ?? 15);

        return response()->json([
            'tenants' => $tenants,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pan_number' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:20',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'owner_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tenant = $this->tenantService->registerTenant($request->all());

        return response()->json([
            'message' => 'Tenant created successfully',
            'tenant' => $tenant,
        ], 201);
    }

    public function show($id)
    {
        $tenant = $this->tenantRepository->findOrFail($id);
        $tenant->load(['subscriptionPlan', 'users', 'restaurants']);

        $subscriptionStatus = $this->tenantService->checkSubscriptionStatus($id);

        return response()->json([
            'tenant' => $tenant,
            'subscription_status' => $subscriptionStatus,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:tenants,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pan_number' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:20',
            'logo' => 'nullable|string',
            'max_users' => 'sometimes|required|integer|min:1',
            'max_branches' => 'sometimes|required|integer|min:1',
            'storage_limit_mb' => 'sometimes|required|integer|min:1',
            'is_active' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tenant = $this->tenantRepository->update($id, $request->all());

        return response()->json([
            'message' => 'Tenant updated successfully',
            'tenant' => $tenant,
        ]);
    }

    public function destroy($id)
    {
        $this->tenantRepository->delete($id);

        return response()->json([
            'message' => 'Tenant deleted successfully',
        ]);
    }

    public function suspend(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $this->tenantService->suspendTenant($id, $request->reason);

        return response()->json([
            'message' => 'Tenant suspended successfully',
        ]);
    }

    public function activate($id)
    {
        $this->tenantService->activateTenant($id);

        return response()->json([
            'message' => 'Tenant activated successfully',
        ]);
    }

    public function upgradeSubscription(Request $request, $id)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $tenant = $this->tenantService->upgradeSubscription($id, $request->subscription_plan_id);

        return response()->json([
            'message' => 'Subscription upgraded successfully',
            'tenant' => $tenant,
        ]);
    }

    public function dashboard()
    {
        $totalTenants = $this->tenantRepository->all()->count();
        $activeTenants = $this->tenantRepository->getActiveTenants()->count();
        $trialTenants = $this->tenantRepository->getTrialTenants()->count();
        $expiredTenants = $this->tenantRepository->getExpiredTenants()->count();

        // Calculate MRR (Monthly Recurring Revenue)
        $activeTenantsWithPlans = $this->tenantRepository->getActiveTenants()
            ->load('subscriptionPlan');
        $mrr = $activeTenantsWithPlans->sum(function ($tenant) {
            return $tenant->subscriptionPlan->monthly_price ?? 0;
        });

        return response()->json([
            'total_tenants' => $totalTenants,
            'active_tenants' => $activeTenants,
            'trial_tenants' => $trialTenants,
            'expired_tenants' => $expiredTenants,
            'mrr' => $mrr,
        ]);
    }
}
