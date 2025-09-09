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
        Schema::create('package_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('package_name');
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
            $table->string('website_name')->nullable();
            $table->string('website_type')->nullable();
            $table->integer('page_count')->nullable();
            $table->string('page_url')->nullable();
            $table->integer('ad_budget')->nullable();
            $table->text('notes')->nullable();
            
            // Order status
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_orders');
    }
};
