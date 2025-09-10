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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            
            // Service information
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('service_name');
            $table->decimal('amount', 10, 2);
            
            // Customer information
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('company')->nullable();
            
            // Billing information
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code');
            $table->string('country');
            
            // Project details
            $table->string('project_name')->nullable();
            $table->string('project_type')->nullable();
            $table->text('requirements')->nullable();
            $table->text('notes')->nullable();
            
            // Payment information
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->nullable();
            $table->integer('total_installments')->default(3);
            $table->integer('current_installment')->default(0);
            $table->json('payment_history')->nullable();
            
            // Order status
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
