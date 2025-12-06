<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
{
    Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('receipt_number', 191)->unique();
    $table->float('total');
    $table->float('discount')->default(0); 
    $table->float('total_after_discount');
    $table->string('note')->nullable(); // ✅ Add note column
    $table->timestamps();
});

}


    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
