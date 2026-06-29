<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('address');
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('municipality')->nullable();
            $table->string('ward')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('invoice_prefix')->default('INV');
            $table->string('printer_type')->default('thermal')->comment('thermal, dot_matrix, a4');
            $table->decimal('tax_rate', 5, 2)->default(13.00)->comment('VAT %');
            $table->decimal('service_charge_rate', 5, 2)->default(0.00);
            $table->string('currency')->default('NPR');
            $table->string('language')->default('en');
            $table->boolean('nepali_date_enabled')->default(true);
            $table->time('opening_time')->default('10:00:00');
            $table->time('closing_time')->default('22:00:00');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
