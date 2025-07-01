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
        Schema::create('batch_medicine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')
                  ->constrained('batches') // Assumes 'batches' table exists
                  ->onDelete('cascade');
            $table->foreignId('medicine_id')
                  ->constrained('medicines') // Assumes 'medicines' table exists
                  ->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 8, 2); // Price for this medicine in the batch
            $table->decimal('ptr', 8, 2)->nullable(); // PTR for this medicine in the batch
            $table->date('expiry_date')->nullable(); // Directly added here
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_medicine');
    }
};