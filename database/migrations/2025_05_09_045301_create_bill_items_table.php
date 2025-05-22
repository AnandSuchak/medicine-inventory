<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('medicine_id'); // Reference to the medicine
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2); // Quantity * Unit price
            $table->timestamps();
    
            // Foreign key constraints
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
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
