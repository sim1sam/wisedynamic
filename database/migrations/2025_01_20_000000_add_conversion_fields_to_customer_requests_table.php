<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_requests', function (Blueprint $table) {
            $table->boolean('is_converted')->default(false)->after('status');
            $table->foreignId('service_order_id')->nullable()->constrained()->onDelete('set null')->after('is_converted');
            $table->timestamp('converted_at')->nullable()->after('service_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('customer_requests', function (Blueprint $table) {
            $table->dropForeign(['service_order_id']);
            $table->dropColumn(['is_converted', 'service_order_id', 'converted_at']);
        });
    }
};