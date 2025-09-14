<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add payment fields to package_orders table
        Schema::table('package_orders', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'pending_verification', 'paid'])->default('unpaid')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
        });
        
        // Add payment fields to service_orders table
        Schema::table('service_orders', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'pending_verification', 'paid'])->default('unpaid')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method']);
        });
        
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method']);
        });
    }
};
