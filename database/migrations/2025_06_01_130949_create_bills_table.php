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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->date('bill_date');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null'); // Optional customer link

            // New GST related fields
            $table->decimal('sub_total_before_tax', 10, 2); // Sum of bill_items.sub_total
            $table->decimal('total_gst_amount', 10, 2)->default(0.00); // Total GST charged on the bill

            $table->decimal('discount_amount', 10, 2)->default(0.00); // Optional discount (applied before or after tax based on business logic)
            $table->decimal('net_amount', 10, 2); // Final amount after all calculations (sub_total + total_gst_amount - discount)

            $table->string('payment_status')->default('pending'); // e.g., pending, paid, cancelled
            $table->text('notes')->nullable();
                        $table->string('status')->default('active');
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