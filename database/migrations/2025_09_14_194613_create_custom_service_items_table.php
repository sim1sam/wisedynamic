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
        Schema::create('custom_service_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_service_request_id')->constrained()->onDelete('cascade');
            $table->string('service_name');
            $table->decimal('amount', 10, 2);
            
            // Marketing service fields
            $table->string('platform')->nullable(); // For marketing services
            $table->text('post_link')->nullable(); // For marketing services
            $table->date('service_date')->nullable(); // For marketing services
            
            // Web/App service fields
            $table->string('domain_name')->nullable(); // For web/app services
            $table->integer('duration_months')->nullable(); // For web/app services
            
            // Common fields
            $table->text('description')->nullable();
            $table->json('additional_data')->nullable(); // For any extra data
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_service_items');
    }
};
