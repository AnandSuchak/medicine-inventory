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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')
                  ->constrained('bills')
                  ->onDelete('cascade');
            $table->foreignId('medicine_id')
                  ->constrained('medicines') // Link to your 'medicines' table
                  ->onDelete('restrict');

            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2); // Price per unit *before* GST

            // New GST related fields for each item
            $table->decimal('gst_rate_percentage', 5, 2)->default(0.00); // e.g., 5.00 for 5% GST
            $table->decimal('item_gst_amount', 10, 2)->default(0.00); // Calculated GST amount for this item (quantity * unit_price * gst_rate_percentage / 100)

            $table->decimal('sub_total', 10, 2); // Quantity * Unit Price (this is the taxable value of the item)
            $table->decimal('total_amount_after_tax', 10, 2); // sub_total + item_gst_amount

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};