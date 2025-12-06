<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('category_id'); // Placed right after id
        $table->string('name');
        $table->decimal('price', 8, 2);
        $table->text('description');
        $table->string('img');
        $table->timestamps();

        // Foreign key constraint
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
    });
}


    public function down()
    {
        Schema::dropIfExists('products');
    }
}
