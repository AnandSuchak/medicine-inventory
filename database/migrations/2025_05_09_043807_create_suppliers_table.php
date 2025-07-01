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
               Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable(); // Phone number, optional
            $table->text('address')->nullable(); // Supplier address, optional
            $table->string('gstin', 15)->nullable(); // GSTIN, optional
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
    
};
