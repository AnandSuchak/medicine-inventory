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
          Schema::rename('batch_medicine', 'purchase_bill_medicine');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::rename('purchase_bill_medicine', 'batch_medicine');
    }
};
