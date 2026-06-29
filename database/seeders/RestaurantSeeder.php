<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\Branch;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        $owner = User::where('user_type', 'owner')->first();
        $manager = User::where('user_type', 'manager')->first();

        $restaurant = Restaurant::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'tenant_id' => $tenant->id,
            'owner_id' => $owner->id,
            'name' => 'Demo Restaurant',
            'slug' => 'demo-restaurant',
            'description' => 'A demo restaurant for testing',
            'pan_number' => '123456789',
            'vat_number' => '987654321',
            'phone' => '+977-9800000000',
            'email' => 'info@demorestaurant.com',
            'address' => 'Thamel, Kathmandu',
            'province' => 'Bagmati',
            'district' => 'Kathmandu',
            'municipality' => 'Kathmandu Metropolitan City',
            'ward' => '4',
            'invoice_prefix' => 'INV',
            'printer_type' => 'thermal',
            'tax_rate' => 13,
            'service_charge_rate' => 10,
            'currency' => 'NPR',
            'language' => 'en',
            'nepali_date_enabled' => true,
            'opening_time' => '10:00:00',
            'closing_time' => '22:00:00',
            'is_active' => true,
        ]);

        // Update owner and manager with restaurant
        $owner->update(['restaurant_id' => $restaurant->id]);
        $manager->update(['restaurant_id' => $restaurant->id, 'branch_id' => null]);

        // Create main branch
        $branch = Branch::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'tenant_id' => $tenant->id,
            'restaurant_id' => $restaurant->id,
            'manager_id' => $manager->id,
            'name' => 'Main Branch',
            'code' => 'BR001',
            'phone' => '+977-9800000000',
            'email' => 'main@demorestaurant.com',
            'address' => 'Thamel, Kathmandu',
            'province' => 'Bagmati',
            'district' => 'Kathmandu',
            'municipality' => 'Kathmandu Metropolitan City',
            'ward' => '4',
            'is_head_office' => true,
            'separate_stock' => false,
            'separate_sales' => false,
            'is_active' => true,
        ]);

        // Update manager with branch
        $manager->update(['branch_id' => $branch->id]);

        // Update other users with branch
        User::whereIn('user_type', ['cashier', 'waiter', 'kitchen'])
            ->update(['restaurant_id' => $restaurant->id, 'branch_id' => $branch->id]);
    }
}
