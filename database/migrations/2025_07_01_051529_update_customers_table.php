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
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('name', 'shop_name');
            $table->string('gst')->nullable()->after('address');
            $table->string('pan')->nullable()->after('gst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('shop_name', 'name');
            $table->dropColumn(['gst', 'pan']);
        });
    }
};
