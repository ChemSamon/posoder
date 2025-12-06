<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiptNumberToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // In the new migration
public function up()
{
    if (!Schema::hasColumn('orders', 'receipt_number')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('receipt_number')->after('id');
        });
    }
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    if (Schema::hasColumn('orders', 'receipt_number')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('receipt_number');
        });
    }
}

}
