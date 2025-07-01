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
        Schema::table('purchase_bill_medicine', function (Blueprint $table) {
            // Rename the old 'batch_id' to 'purchase_bill_id' to be more accurate
            $table->renameColumn('batch_id', 'purchase_bill_id');

            // Add the manufacturer batch number
            $table->string('batch_no')->after('medicine_id');

            // Remove columns that are now stored in the medicine_batches table or medicines table
            $table->dropColumn(['ptr', 'gst_percent', 'expiry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_bill_medicine', function (Blueprint $table) {
            $table->renameColumn('purchase_bill_id', 'batch_id');
            $table->dropColumn('batch_no');
            $table->decimal('ptr', 10, 2)->nullable();
            $table->decimal('gst_percent', 5, 2)->default(0.00);
            $table->date('expiry_date')->nullable();
        });
    }
};