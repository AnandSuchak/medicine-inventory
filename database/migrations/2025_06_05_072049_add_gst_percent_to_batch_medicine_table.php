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
        Schema::table('batch_medicine', function (Blueprint $table) {
            // Add the gst_percent column
            // We'll make it a decimal, similar to PTR, and give it a default value.
            $table->decimal('gst_percent', 5, 2)->default(0)->after('ptr');
            // If 'ptr' doesn't exist, you can place it after any other existing column.
            // For example, if you want it at the end: $table->decimal('gst_percent', 5, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_medicine', function (Blueprint $table) {
            // Drop the column if rolling back the migration
            $table->dropColumn('gst_percent');
        });
    }
};