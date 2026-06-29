<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create subscription plans
        $this->call(SubscriptionPlanSeeder::class);
        
        // Create tenants
        $this->call(TenantSeeder::class);
        
        // Create users
        $this->call(UserSeeder::class);
        
        // Create restaurants
        $this->call(RestaurantSeeder::class);
        
        // Create menu categories and items
        $this->call(MenuSeeder::class);
        
        // Create food menu with items and images
        $this->call(FoodMenuSeeder::class);
    }
}
