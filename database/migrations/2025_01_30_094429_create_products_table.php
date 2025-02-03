<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('Pcode')->unique(); // Product code (unique)
            $table->string('Pname'); // Product name
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Important for data integrity
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};