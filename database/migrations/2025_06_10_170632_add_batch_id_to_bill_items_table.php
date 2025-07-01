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
            // Adding the batch_id foreign key
            // Ensure 'batches' table exists before running this migration.
            $table->foreignId('batch_id')
                  ->nullable() // Making it nullable temporarily if existing rows might not have a batch, then update existing data
                  ->constrained('batches')
                  ->onDelete('restrict') // Prevent deletion of a batch if its stock has been billed
                  ->after('medicine_id'); // Place it after medicine_id for logical order

            // You might want to make this not nullable after all existing bill items are processed
            // or if you ensure all future bill items will always have a batch_id.
            // If you change to ->nullable(false), you'd need to add default or update existing rows first.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropForeign(['batch_id']); // Drop the foreign key constraint first
            $table->dropColumn('batch_id');    // Then drop the column
        });
    }
};