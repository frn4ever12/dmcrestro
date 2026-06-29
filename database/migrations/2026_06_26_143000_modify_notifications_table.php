<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Add custom columns for restaurant management
            $table->foreignId('user_id')->nullable()->after('id');
            $table->foreignId('tenant_id')->nullable()->after('user_id');
            $table->string('title')->nullable()->after('type');
            $table->text('message')->nullable()->after('title');
            $table->string('icon')->default('fas fa-bell')->after('message');
            $table->string('link')->nullable()->after('icon');
            $table->boolean('is_read')->default(false)->after('read_at');
            
            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['user_id', 'tenant_id', 'title', 'message', 'icon', 'link', 'is_read']);
        });
    }
};
