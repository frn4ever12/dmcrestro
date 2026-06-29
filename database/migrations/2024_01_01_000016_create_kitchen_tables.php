<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kitchen_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('table_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name');
            $table->integer('quantity');
            $table->json('modifiers')->nullable();
            $table->text('notes')->nullable();
            $table->string('kitchen_section')->nullable();
            $table->enum('priority', ['normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['pending', 'preparing', 'ready', 'served', 'cancelled'])->default('pending');
            $table->foreignId('chef_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('preparing_started_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->integer('preparation_time')->nullable()->comment('in minutes');
            $table->timestamps();
        });

        Schema::create('kitchen_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('name_np')->nullable();
            $table->text('description')->nullable();
            $table->string('printer_ip')->nullable();
            $table->string('printer_name')->nullable();
            $table->boolean('sound_enabled')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('kitchen_display_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('show_preparation_time')->default(true);
            $table->boolean('show_item_image')->default(false);
            $table->boolean('auto_refresh')->default(true);
            $table->integer('refresh_interval')->default(30)->comment('in seconds');
            $table->boolean('sound_notification')->default(true);
            $table->string('sound_file')->nullable();
            $table->string('theme')->default('dark');
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kitchen_display_settings');
        Schema::dropIfExists('kitchen_sections');
        Schema::dropIfExists('kitchen_orders');
    }
};
