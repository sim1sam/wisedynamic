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
        Schema::table('custom_service_requests', function (Blueprint $table) {
            $table->string('payment_status')->nullable()->after('ssl_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_service_requests', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};
