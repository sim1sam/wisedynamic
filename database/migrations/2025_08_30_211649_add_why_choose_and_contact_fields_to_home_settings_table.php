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
        Schema::table('home_settings', function (Blueprint $table) {
            // Why Choose Us section
            $table->string('why_choose_title')->nullable();
            $table->text('why_choose_subtitle')->nullable();
            $table->json('why_choose_items')->nullable();
            $table->integer('why_choose_clients_count')->nullable();
            $table->string('why_choose_experience')->nullable();
            
            // Let's Build Something Amazing section
            $table->string('contact_title')->nullable();
            $table->text('contact_subtitle')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            // Drop Why Choose Us section columns
            $table->dropColumn([
                'why_choose_title',
                'why_choose_subtitle',
                'why_choose_items',
                'why_choose_clients_count',
                'why_choose_experience',
                
                // Drop Let's Build Something Amazing section columns
                'contact_title',
                'contact_subtitle',
                'contact_phone',
                'contact_email',
                'contact_location'
            ]);
        });
    }
};
