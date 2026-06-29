<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('group')->default('general');
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('string')->comment('string, integer, boolean, json, array');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            
            $table->unique(['tenant_id', 'restaurant_id', 'branch_id', 'group', 'key']);
        });

        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('thermal')->comment('thermal, dot_matrix, a4');
            $table->string('connection_type')->default('network')->comment('network, usb, bluetooth');
            $table->string('ip_address')->nullable();
            $table->string('port')->nullable();
            $table->enum('paper_size', ['58mm', '80mm', 'a4'])->default('80mm');
            $table->integer('characters_per_line')->default(32);
            $table->boolean('is_default')->default(false);
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('rate', 5, 2);
            $table->enum('type', ['vat', 'service_charge', 'other'])->default('vat');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->timestamps();
        });

        Schema::create('sms_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->integer('total_credits')->default(0);
            $table->integer('used_credits')->default(0);
            $table->integer('remaining_credits')->default(0);
            $table->timestamp('expiry_date')->nullable();
            $table->timestamps();
        });

        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('phone');
            $table->text('message');
            $table->enum('status', ['sent', 'failed', 'pending'])->default('pending');
            $table->string('gateway')->nullable();
            $table->string('message_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('sms_credits');
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('printers');
        Schema::dropIfExists('settings');
    }
};
