<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateOwnerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = \App\Models\Tenant::first();
        
        if ($tenant) {
            $user = \App\Models\User::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'name' => 'Restaurant Owner',
                'email' => 'owner@restaurant.com',
                'password' => 'password123',
                'user_type' => 'owner',
                'tenant_id' => $tenant->id,
            ]);
            
            $tenant->users()->attach($user->id, ['role' => 'owner']);
            
            $this->command->info('Owner user created: owner@restaurant.com / password123');
        } else {
            $this->command->error('No tenant found to create owner user for.');
        }
    }
}
