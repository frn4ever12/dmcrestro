<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 10, 2)->default(0);
            $table->decimal('yearly_price', 10, 2)->default(0);
            $table->integer('trial_days')->default(14);
            $table->integer('max_restaurants')->default(1);
            $table->integer('max_branches')->default(5);
            $table->integer('max_users')->default(10);
            $table->integer('storage_limit_mb')->default(1024);
            $table->boolean('pos_module')->default(true);
            $table->boolean('qr_menu_module')->default(true);
            $table->boolean('inventory_module')->default(true);
            $table->boolean('accounting_module')->default(true);
            $table->boolean('hrm_module')->default(false);
            $table->boolean('crm_module')->default(false);
            $table->boolean('kitchen_display_module')->default(true);
            $table->boolean('online_ordering_module')->default(false);
            $table->boolean('delivery_module')->default(false);
            $table->boolean('api_access')->default(true);
            $table->boolean('white_label')->default(false);
            $table->boolean('custom_domain')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('features')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
