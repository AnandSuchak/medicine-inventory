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
        Schema::table('suppliers', function (Blueprint $table) {
            // Add drug_license_id column
            // Assuming it's a string and can be nullable. Adjust as needed.
            $table->string('drug_license_id', 50)->nullable()->after('gstin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Drop the column if rolling back the migration
            $table->dropColumn('drug_license_id');
        });
    }
};

