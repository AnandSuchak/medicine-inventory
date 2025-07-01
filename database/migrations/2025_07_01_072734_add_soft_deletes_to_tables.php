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
        // Check for 'bills' table
        if (!Schema::hasColumn('bills', 'deleted_at')) {
            Schema::table('bills', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Check for 'bill_items' table
        if (!Schema::hasColumn('bill_items', 'deleted_at')) {
            Schema::table('bill_items', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Check for 'purchase_bills' table
        if (!Schema::hasColumn('purchase_bills', 'deleted_at')) {
            Schema::table('purchase_bills', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Check for 'purchase_bill_medicine' table
        if (!Schema::hasColumn('purchase_bill_medicine', 'deleted_at')) {
            Schema::table('purchase_bill_medicine', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add dropSoftDeletes with checks as well for robust rollback
        if (Schema::hasColumn('bills', 'deleted_at')) {
            Schema::table('bills', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('bill_items', 'deleted_at')) {
            Schema::table('bill_items', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('purchase_bills', 'deleted_at')) {
            Schema::table('purchase_bills', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('purchase_bill_medicine', 'deleted_at')) {
            Schema::table('purchase_bill_medicine', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};