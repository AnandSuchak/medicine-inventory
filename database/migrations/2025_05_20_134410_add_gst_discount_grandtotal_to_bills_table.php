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
    Schema::table('bills', function (Blueprint $table) {
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('gst', 10, 2)->default(0);
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('grand_total', 10, 2)->default(0);
    });
}

public function down()
{
    Schema::table('bills', function (Blueprint $table) {
        $table->dropColumn(['subtotal', 'gst', 'discount', 'grand_total']);
    });
}
};
