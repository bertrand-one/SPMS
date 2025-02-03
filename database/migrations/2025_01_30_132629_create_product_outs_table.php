<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_outs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Pcode'); // Foreign key
            $table->integer('Outquantity');
            $table->decimal('Outprice', 10, 2); // Decimal for price
            $table->date('date');
            $table->timestamps();

            $table->foreign('Pcode')->references('id')->on('products')->onDelete('cascade'); // Foreign key constraint
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_outs');
    }
};
