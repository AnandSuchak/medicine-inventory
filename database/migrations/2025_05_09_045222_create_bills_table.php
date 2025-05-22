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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->unsignedBigInteger('customer_id'); // Assuming a customer table exists
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('Ordered'); // Bill status (Ordered, Paid, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
