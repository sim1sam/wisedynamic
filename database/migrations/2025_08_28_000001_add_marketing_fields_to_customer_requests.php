<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_requests', function (Blueprint $table) {
            $table->string('page_name')->after('user_id');
            $table->string('social_media')->after('page_name');
            $table->decimal('ads_budget_bdt', 12, 2)->after('social_media');
            $table->unsignedInteger('days')->after('ads_budget_bdt');
            $table->string('post_link')->nullable()->after('days');
        });
    }

    public function down(): void
    {
        Schema::table('customer_requests', function (Blueprint $table) {
            $table->dropColumn(['page_name','social_media','ads_budget_bdt','days','post_link']);
        });
    }
};
