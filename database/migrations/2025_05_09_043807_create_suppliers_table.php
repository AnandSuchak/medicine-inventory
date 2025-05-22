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
            $table->string('name'); // Name of the supplier
            $table->string('contact_number')->nullable(); // Contact number of the supplier
            $table->string('email')->nullable(); // Email address
            $table->text('address')->nullable(); // Address of the supplier
            $table->enum('status', ['active', 'inactive'])->default('active'); // Active or Inactive status
            $table->timestamps(); // Created at and updated at timestamps
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
    
};
