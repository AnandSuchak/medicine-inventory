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
    Schema::table('bill_items', function (Blueprint $table) {
        $table->unsignedBigInteger('batch_id')->nullable()->after('medicine_id');

        // If you have a `batches` table
        $table->foreign('batch_id')->references('id')->on('batches')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('bill_items', function (Blueprint $table) {
        $table->dropForeign(['batch_id']);
        $table->dropColumn('batch_id');
    });
}

};
