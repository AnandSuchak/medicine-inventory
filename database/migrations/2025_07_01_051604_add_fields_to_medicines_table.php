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
        Schema::table('medicines', function (Blueprint $table) {
            $table->decimal('gst', 5, 2)->default(0.00)->after('unit');
            $table->string('pack_size')->nullable()->after('gst');
            $table->string('mfg_company_name')->nullable()->after('pack_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['gst', 'pack_size', 'mfg_company_name']);
        });
    }
};