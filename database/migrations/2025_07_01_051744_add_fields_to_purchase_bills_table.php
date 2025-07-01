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
        Schema::table('purchase_bills', function (Blueprint $table) {
            $table->decimal('cash_discount_percentage', 5, 2)->default(0.00)->after('status');
            $table->decimal('cgst_amount', 10, 2)->default(0.00)->after('cash_discount_percentage');
            $table->decimal('sgst_amount', 10, 2)->default(0.00)->after('cgst_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_bills', function (Blueprint $table) {
            $table->dropColumn(['cash_discount_percentage', 'cgst_amount', 'sgst_amount']);
        });
    }
};