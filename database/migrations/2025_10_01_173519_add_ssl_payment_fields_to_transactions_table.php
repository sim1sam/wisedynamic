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
        Schema::table('transactions', function (Blueprint $table) {
            // SSL Commerz specific fields
            $table->string('ssl_transaction_id')->nullable()->after('transaction_number');
            $table->string('ssl_session_id')->nullable()->after('ssl_transaction_id');
            $table->string('ssl_bank_transaction_id')->nullable()->after('ssl_session_id');
            $table->string('ssl_card_type')->nullable()->after('ssl_bank_transaction_id');
            $table->string('ssl_card_no')->nullable()->after('ssl_card_type');
            $table->string('ssl_card_issuer')->nullable()->after('ssl_card_no');
            $table->string('ssl_card_brand')->nullable()->after('ssl_card_issuer');
            $table->string('ssl_card_issuer_country')->nullable()->after('ssl_card_brand');
            $table->string('ssl_card_issuer_country_code')->nullable()->after('ssl_card_issuer_country');
            $table->string('ssl_currency_type')->nullable()->after('ssl_card_issuer_country_code');
            $table->decimal('ssl_amount', 10, 2)->nullable()->after('ssl_currency_type');
            $table->decimal('ssl_currency_amount', 10, 2)->nullable()->after('ssl_amount');
            $table->string('ssl_currency_rate')->nullable()->after('ssl_currency_amount');
            $table->string('ssl_base_fair')->nullable()->after('ssl_currency_rate');
            $table->string('ssl_value_a')->nullable()->after('ssl_base_fair');
            $table->string('ssl_value_b')->nullable()->after('ssl_value_a');
            $table->string('ssl_value_c')->nullable()->after('ssl_value_b');
            $table->string('ssl_value_d')->nullable()->after('ssl_value_c');
            $table->string('ssl_risk_level')->nullable()->after('ssl_value_d');
            $table->string('ssl_risk_title')->nullable()->after('ssl_risk_level');
            
            // Customer information for SSL payments
            $table->string('customer_name')->nullable()->after('ssl_risk_title');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone')->nullable()->after('customer_email');
            $table->text('customer_address')->nullable()->after('customer_phone');
            $table->string('customer_city')->nullable()->after('customer_address');
            $table->string('customer_state')->nullable()->after('customer_city');
            $table->string('customer_postcode')->nullable()->after('customer_state');
            $table->string('customer_country')->nullable()->after('customer_postcode');
            
            // Order details
            $table->string('order_type')->nullable()->after('customer_country'); // package, service, fund, custom_service
            $table->text('order_details')->nullable()->after('order_type'); // JSON field for order items
            
            // SSL response data
            $table->text('ssl_response_data')->nullable()->after('order_details'); // Full SSL response
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'ssl_transaction_id',
                'ssl_session_id',
                'ssl_bank_transaction_id',
                'ssl_card_type',
                'ssl_card_no',
                'ssl_card_issuer',
                'ssl_card_brand',
                'ssl_card_issuer_country',
                'ssl_card_issuer_country_code',
                'ssl_currency_type',
                'ssl_amount',
                'ssl_currency_amount',
                'ssl_currency_rate',
                'ssl_base_fair',
                'ssl_value_a',
                'ssl_value_b',
                'ssl_value_c',
                'ssl_value_d',
                'ssl_risk_level',
                'ssl_risk_title',
                'customer_name',
                'customer_email',
                'customer_phone',
                'customer_address',
                'customer_city',
                'customer_state',
                'customer_postcode',
                'customer_country',
                'order_type',
                'order_details',
                'ssl_response_data'
            ]);
        });
    }
};
