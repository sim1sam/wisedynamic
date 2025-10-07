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
        // Create transactions table if it doesn't exist
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_number')->unique();
                $table->decimal('amount', 10, 2);
                $table->string('payment_method');
                $table->string('status');
                $table->text('notes')->nullable();
                $table->string('ssl_transaction_id')->nullable();
                $table->string('ssl_status')->nullable();
                $table->text('ssl_fail_reason')->nullable();
                $table->string('ssl_bank_transaction_id')->nullable();
                $table->string('ssl_card_type')->nullable();
                $table->string('ssl_card_no')->nullable();
                $table->string('ssl_card_issuer')->nullable();
                $table->string('ssl_card_brand')->nullable();
                $table->string('ssl_card_issuer_country')->nullable();
                $table->string('ssl_card_issuer_country_code')->nullable();
                $table->string('ssl_currency_type')->nullable();
                $table->decimal('ssl_amount', 10, 2)->nullable();
                $table->decimal('ssl_currency_amount', 10, 2)->nullable();
                $table->decimal('ssl_currency_rate', 10, 4)->nullable();
                $table->decimal('ssl_base_fair', 10, 2)->nullable();
                $table->string('ssl_value_a')->nullable();
                $table->string('ssl_value_b')->nullable();
                $table->string('ssl_value_c')->nullable();
                $table->string('ssl_value_d')->nullable();
                $table->string('ssl_risk_level')->nullable();
                $table->string('ssl_risk_title')->nullable();
                $table->json('ssl_response_data')->nullable();
                
                // Customer information
                $table->string('customer_name')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_phone')->nullable();
                $table->string('customer_address')->nullable();
                $table->string('customer_city')->nullable();
                $table->string('customer_state')->nullable();
                $table->string('customer_postcode')->nullable();
                $table->string('customer_country')->nullable();
                
                // Order details
                $table->string('order_type')->nullable();
                $table->json('order_details')->nullable();
                
                // Foreign key columns without constraints for now
                $table->unsignedBigInteger('package_order_id')->nullable();
                $table->unsignedBigInteger('service_order_id')->nullable();
                $table->unsignedBigInteger('custom_service_request_id')->nullable();
                $table->unsignedBigInteger('fund_request_id')->nullable();
                
                $table->timestamps();
            });
        }

        // Create slides table if it doesn't exist
        if (!Schema::hasTable('slides')) {
            Schema::create('slides', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('subtitle')->nullable();
                $table->string('price_text')->nullable();
                $table->enum('image_source', ['url', 'upload'])->default('url');
                $table->string('image_url')->nullable();
                $table->string('image_path')->nullable();
                $table->integer('position')->default(0);
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }

        // Create website_settings table if it doesn't exist
        if (!Schema::hasTable('website_settings')) {
            Schema::create('website_settings', function (Blueprint $table) {
                $table->id();
                $table->string('site_name');
                $table->string('site_tagline')->nullable();
                $table->string('site_logo')->nullable();
                $table->string('site_favicon')->nullable();
                $table->string('primary_color')->default('#0066cc');
                $table->string('secondary_color')->default('#ff9900');
                $table->string('footer_text')->nullable();
                $table->string('facebook_url')->nullable();
                $table->string('twitter_url')->nullable();
                $table->string('instagram_url')->nullable();
                $table->string('linkedin_url')->nullable();
                $table->string('youtube_url')->nullable();
                $table->string('meta_title')->nullable();
                $table->string('meta_description')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->timestamps();
            });
        }
        
        // Create service_categories table if it doesn't exist
        if (!Schema::hasTable('service_categories')) {
            Schema::create('service_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->string('image')->nullable();
                $table->boolean('status')->default(true);
                $table->integer('position')->default(0);
                $table->timestamps();
            });
        }
        
        // Create services table if it doesn't exist
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('service_category_id')->nullable();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->text('features')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->string('image')->nullable();
                $table->boolean('status')->default(true);
                $table->integer('position')->default(0);
                $table->timestamps();
            });
        }
        
        // Create package_categories table if it doesn't exist
        if (!Schema::hasTable('package_categories')) {
            Schema::create('package_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->string('image')->nullable();
                $table->boolean('status')->default(true);
                $table->integer('position')->default(0);
                $table->timestamps();
            });
        }
        
        // Create packages table if it doesn't exist
        if (!Schema::hasTable('packages')) {
            Schema::create('packages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('package_category_id')->nullable();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->text('features')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->integer('duration')->default(30); // in days
                $table->string('image')->nullable();
                $table->boolean('status')->default(true);
                $table->integer('position')->default(0);
                $table->timestamps();
            });
        }
        
        // Create home_settings table if it doesn't exist
        if (!Schema::hasTable('home_settings')) {
            Schema::create('home_settings', function (Blueprint $table) {
                $table->id();
                $table->string('hero_title')->nullable();
                $table->text('hero_description')->nullable();
                $table->string('hero_button_text')->nullable();
                $table->string('hero_button_url')->nullable();
                $table->string('hero_image')->nullable();
                $table->string('about_title')->nullable();
                $table->text('about_description')->nullable();
                $table->string('about_image')->nullable();
                $table->string('services_title')->nullable();
                $table->text('services_description')->nullable();
                $table->string('packages_title')->nullable();
                $table->text('packages_description')->nullable();
                $table->string('why_choose_title')->nullable();
                $table->text('why_choose_description')->nullable();
                $table->string('contact_title')->nullable();
                $table->text('contact_description')->nullable();
                $table->string('whatsapp_number')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop tables in down method to avoid data loss
    }
};
