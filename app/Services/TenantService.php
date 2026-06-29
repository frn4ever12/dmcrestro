<?php

namespace App\Services;

use App\Models\Tenant;
use App\Repositories\TenantRepository;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantService extends BaseService
{
    private TenantRepository $tenantRepository;

    public function __construct(TenantRepository $tenantRepository)
    {
        parent::__construct($tenantRepository);
        $this->tenantRepository = $tenantRepository;
    }

    public function registerTenant(array $data): Tenant
    {
        return DB::transaction(function () use ($data) {
            $plan = SubscriptionPlan::findOrFail($data['subscription_plan_id'] ?? 1);

            $tenant = $this->tenantRepository->create([
                'uuid' => Str::uuid(),
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'country' => $data['country'] ?? 'Nepal',
                'pan_number' => $data['pan_number'] ?? null,
                'vat_number' => $data['vat_number'] ?? null,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays($plan->trial_days),
                'subscription_ends_at' => null,
                'subscription_plan_id' => $plan->id,
                'max_users' => $plan->max_users,
                'max_branches' => $plan->max_branches,
                'storage_limit_mb' => $plan->storage_limit_mb,
                'is_active' => true,
            ]);

            // Create owner user
            $user = \App\Models\User::create([
                'uuid' => Str::uuid(),
                'name' => $data['owner_name'] ?? $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'phone' => $data['phone'] ?? null,
                'user_type' => 'owner',
                'tenant_id' => $tenant->id,
                'is_active' => true,
            ]);

            // Attach user to tenant
            $tenant->users()->attach($user->id, [
                'role' => 'owner',
                'permissions' => ['*'],
            ]);

            return $tenant;
        });
    }

    public function upgradeSubscription(int $tenantId, int $planId): Tenant
    {
        $tenant = $this->tenantRepository->findOrFail($tenantId);
        $plan = SubscriptionPlan::findOrFail($planId);

        $tenant->update([
            'subscription_plan_id' => $planId,
            'subscription_ends_at' => now()->addYear(),
            'status' => 'active',
            'max_users' => $plan->max_users,
            'max_branches' => $plan->max_branches,
            'storage_limit_mb' => $plan->storage_limit_mb,
        ]);

        return $tenant;
    }

    public function checkSubscriptionStatus(int $tenantId): array
    {
        $tenant = $this->tenantRepository->findOrFail($tenantId);

        return [
            'status' => $tenant->status,
            'is_trial' => $tenant->isTrial(),
            'is_expired' => $tenant->isExpired(),
            'trial_ends_at' => $tenant->trial_ends_at,
            'subscription_ends_at' => $tenant->subscription_ends_at,
            'days_remaining' => $tenant->subscription_ends_at 
                ? now()->diffInDays($tenant->subscription_ends_at) 
                : null,
            'can_add_user' => $tenant->canAddUser(),
            'can_add_branch' => $tenant->canAddBranch(),
            'storage_usage_percentage' => $tenant->getStorageUsagePercentage(),
        ];
    }

    public function suspendTenant(int $tenantId, string $reason): bool
    {
        $tenant = $this->tenantRepository->findOrFail($tenantId);
        return $tenant->update([
            'status' => 'suspended',
            'is_active' => false,
        ]);
    }

    public function activateTenant(int $tenantId): bool
    {
        $tenant = $this->tenantRepository->findOrFail($tenantId);
        return $tenant->update([
            'status' => 'active',
            'is_active' => true,
        ]);
    }
}
