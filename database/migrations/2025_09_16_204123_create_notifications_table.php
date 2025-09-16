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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // order, service_order, fund_request, custom_service, message
            $table->string('title');
            $table->text('message');
            $table->string('url')->nullable(); // link to view the item
            $table->boolean('is_read')->default(false);
            $table->unsignedBigInteger('related_id')->nullable(); // ID of the related item
            $table->string('related_type')->nullable(); // Model class name
            $table->timestamps();
            
            $table->index(['is_read', 'created_at']);
            $table->index(['type', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
