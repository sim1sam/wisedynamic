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
        Schema::table('package_orders', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->nullable();
            $table->integer('total_installments')->default(3);
            $table->integer('current_installment')->default(0);
            $table->json('payment_history')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_orders', function (Blueprint $table) {
            $table->dropColumn([
                'paid_amount',
                'due_amount',
                'total_installments',
                'current_installment',
                'payment_history'
            ]);
        });
    }
};
