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
        Schema::table('bill_items', function (Blueprint $table) {
            // First, drop the old batch_id column if it exists.
            // The foreign key was likely named 'bill_items_batch_id_foreign'.
            // We use a try-catch block in case it was already removed or named differently.
            try {
                $table->dropForeign(['batch_id']);
            } catch (\Exception $e) {
                // Ignore if the foreign key doesn't exist
            }
            $table->dropColumn('batch_id');

            // Now, add the new column to link to the correct inventory batch.
            $table->foreignId('medicine_batch_id')->nullable()->after('medicine_id')->constrained('medicine_batches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropForeign(['medicine_batch_id']);
            $table->dropColumn('medicine_batch_id');

            // Add the old column back
            $table->foreignId('batch_id')->nullable()->constrained('purchase_bills')->onDelete('cascade');
        });
    }
};