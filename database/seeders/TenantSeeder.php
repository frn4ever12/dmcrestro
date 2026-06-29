<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $plan = SubscriptionPlan::where('slug', 'professional')->first();

        Tenant::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Demo Restaurant',
            'slug' => 'demo-restaurant',
            'email' => 'demo@restaurant.com',
            'phone' => '+977-9800000000',
            'address' => 'Kathmandu, Nepal',
            'city' => 'Kathmandu',
            'country' => 'Nepal',
            'pan_number' => '123456789',
            'vat_number' => '987654321',
            'subscription_plan_id' => $plan->id,
            'trial_ends_at' => now()->addDays(14),
            'subscription_ends_at' => now()->addYear(),
            'max_users' => $plan->max_users,
            'max_branches' => $plan->max_branches,
            'storage_limit_mb' => $plan->storage_limit_mb,
            'status' => 'active',
            'is_active' => true,
        ]);
    }
}
