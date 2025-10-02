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
            $table->text('admin_notes')->nullable()->after('notes');
            $table->unsignedBigInteger('updated_by_admin')->nullable()->after('admin_notes');
            $table->timestamp('admin_updated_at')->nullable()->after('updated_by_admin');
            
            // Add foreign key constraint for admin user
            $table->foreign('updated_by_admin')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['updated_by_admin']);
            $table->dropColumn(['admin_notes', 'updated_by_admin', 'admin_updated_at']);
        });
    }
};
