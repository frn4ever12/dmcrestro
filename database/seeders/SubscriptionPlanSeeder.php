<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Perfect for small restaurants',
                'monthly_price' => 1999,
                'yearly_price' => 19999,
                'trial_days' => 14,
                'max_restaurants' => 1,
                'max_branches' => 1,
                'max_users' => 5,
                'storage_limit_mb' => 1024,
                'pos_module' => true,
                'qr_menu_module' => false,
                'inventory_module' => false,
                'accounting_module' => false,
                'hrm_module' => false,
                'crm_module' => false,
                'kitchen_display_module' => false,
                'online_ordering_module' => false,
                'delivery_module' => false,
                'api_access' => false,
                'white_label' => false,
                'custom_domain' => false,
                'priority_support' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'For growing restaurant chains',
                'monthly_price' => 4999,
                'yearly_price' => 49999,
                'trial_days' => 14,
                'max_restaurants' => 3,
                'max_branches' => 5,
                'max_users' => 20,
                'storage_limit_mb' => 5120,
                'pos_module' => true,
                'qr_menu_module' => true,
                'inventory_module' => true,
                'accounting_module' => true,
                'hrm_module' => false,
                'crm_module' => true,
                'kitchen_display_module' => true,
                'online_ordering_module' => true,
                'delivery_module' => false,
                'api_access' => true,
                'white_label' => false,
                'custom_domain' => false,
                'priority_support' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large restaurant chains',
                'monthly_price' => 9999,
                'yearly_price' => 99999,
                'trial_days' => 30,
                'max_restaurants' => 10,
                'max_branches' => 50,
                'max_users' => 100,
                'storage_limit_mb' => 20480,
                'pos_module' => true,
                'qr_menu_module' => true,
                'inventory_module' => true,
                'accounting_module' => true,
                'hrm_module' => true,
                'crm_module' => true,
                'kitchen_display_module' => true,
                'online_ordering_module' => true,
                'delivery_module' => true,
                'api_access' => true,
                'white_label' => true,
                'custom_domain' => true,
                'priority_support' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
