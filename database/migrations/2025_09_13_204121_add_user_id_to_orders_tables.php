<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add user_id to package_orders table
        Schema::table('package_orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });
        
        // Add user_id to service_orders table
        Schema::table('service_orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });
        
        // Update existing orders to link with users based on email
        DB::statement('
            UPDATE package_orders 
            SET user_id = (SELECT id FROM users WHERE email = package_orders.email LIMIT 1) 
            WHERE user_id IS NULL
        ');
        
        DB::statement('
            UPDATE service_orders 
            SET user_id = (SELECT id FROM users WHERE email = service_orders.email LIMIT 1) 
            WHERE user_id IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
