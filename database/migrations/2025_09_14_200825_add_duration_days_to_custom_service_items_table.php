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
        Schema::table('custom_service_items', function (Blueprint $table) {
            $table->integer('duration_days')->nullable()->after('service_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_service_items', function (Blueprint $table) {
            $table->dropColumn('duration_days');
        });
    }
};
