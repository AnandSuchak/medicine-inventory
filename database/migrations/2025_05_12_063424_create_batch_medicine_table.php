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
     Schema::create('batch_medicine', function (Blueprint $table) {
            $table->id();
            // Foreign key for batches
            $table->foreignId('batch_id')
                  ->constrained('batches')    // Automatically uses batch_id as the foreign key
                  ->onDelete('cascade');      // Cascade delete for the batch
            // Foreign key for medicines
            $table->foreignId('medicine_id')
                  ->constrained('medicines')  // Automatically uses medicine_id as the foreign key
                  ->onDelete('cascade');     // Cascade delete for the medicine
            $table->integer('quantity');  // Quantity of medicine in this batch
            $table->decimal('price', 8, 2);  // Price for this medicine in the batch
            $table->decimal('ptr', 8, 2)->nullable();  // PTR for this medicine in the batch
            $table->timestamps();  // Created and updated timestamps
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
