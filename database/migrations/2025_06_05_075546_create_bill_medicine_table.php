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
        Schema::create('bill_medicine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // Quantity of this medicine in this bill
            $table->decimal('ptr_at_sale', 10, 2); // PTR at the time of sale
            $table->decimal('gst_percent_at_sale', 5, 2); // GST percentage at the time of sale
            $table->decimal('sub_total', 10, 2); // Subtotal for this specific medicine item (quantity * ptr_at_sale)
            $table->decimal('tax_amount', 10, 2); // Tax amount for this specific medicine item (sub_total * gst_percent_at_sale)
            $table->timestamps();

            // Ensure unique combination of bill and medicine
            $table->unique(['bill_id', 'medicine_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_medicine');
    }
};