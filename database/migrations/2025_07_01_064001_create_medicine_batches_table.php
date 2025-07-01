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
        Schema::create('medicine_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->string('batch_no'); // The manufacturer's batch number
            $table->date('expiry_date');
            $table->integer('quantity')->default(0);
            $table->decimal('purchase_price', 10, 2); // The price we bought it for
            $table->decimal('ptr', 10, 2)->nullable(); // Price to Retailer
            $table->decimal('mrp', 10, 2)->nullable(); // Maximum Retail Price
            $table->timestamps();

            // A medicine can only have one entry per unique manufacturer batch number
            $table->unique(['medicine_id', 'batch_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_batches');
    }
};