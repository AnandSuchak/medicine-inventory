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
    Schema::create('batches', function (Blueprint $table) {
        $table->id();  // Auto-incrementing primary key
        $table->string('batch_number')->unique();  // Unique batch number
        $table->string('status')->default('Active');  // Default status
        $table->bigInteger('supplier_id')->unsigned();  // Supplier ID (no nullable)
        $table->timestamps();  // Created at and Updated at
        $table->date('purchase_date');

        // Add foreign key constraint for supplier_id (no nullable)
        $table->foreign('supplier_id')
              ->references('id')
              ->on('suppliers')
              ->onDelete('cascade');  // Optional: If a supplier is deleted, delete all associated batches
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
