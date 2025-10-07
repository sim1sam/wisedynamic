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
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                // Add SSL status fields for API updates (only if they don't exist)
                if (!Schema::hasColumn('transactions', 'ssl_status')) {
                    $table->string('ssl_status')->nullable()->after('ssl_transaction_id');
                }
                if (!Schema::hasColumn('transactions', 'ssl_fail_reason')) {
                    $table->text('ssl_fail_reason')->nullable()->after('ssl_status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn([
                    'ssl_status',
                    'ssl_fail_reason'
                ]);
            });
        }
    }
};
