<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'uuid' => \Str::uuid(),
            'name' => 'Super Admin',
            'email' => 'superadmin@nepalrestaurantsaas.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'tenant_id' => null,
        ]);
    }
}
