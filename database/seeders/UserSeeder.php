<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();

        // Super Admin
        User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'phone' => '+977-9800000001',
            'user_type' => 'super_admin',
            'is_active' => true,
        ]);

        // Owner
        $owner = User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Restaurant Owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
            'phone' => '+977-9800000002',
            'user_type' => 'owner',
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ]);

        // Attach owner to tenant
        $tenant->users()->attach($owner->id, [
            'role' => 'owner',
            'permissions' => json_encode(['all']),
        ]);

        // Manager
        User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Restaurant Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'phone' => '+977-9800000003',
            'user_type' => 'manager',
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ]);

        // Cashier
        User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Cashier',
            'email' => 'cashier@example.com',
            'password' => Hash::make('password'),
            'phone' => '+977-9800000004',
            'user_type' => 'cashier',
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ]);

        // Waiter
        User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Waiter',
            'email' => 'waiter@example.com',
            'password' => Hash::make('password'),
            'phone' => '+977-9800000005',
            'user_type' => 'waiter',
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ]);

        // Kitchen Staff
        User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'Kitchen Staff',
            'email' => 'kitchen@example.com',
            'password' => Hash::make('password'),
            'phone' => '+977-9800000006',
            'user_type' => 'kitchen',
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ]);
    }
}
