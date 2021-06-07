<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCnnoStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_cnno_stock', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stock_cnno')->nullable();
            $table->unsignedInteger('stock_item_id')->nullable();
            $table->unsignedInteger('stock_iss_ro_id')->nullable();
            $table->unsignedInteger('stock_iss_bo_id')->nullable();
            $table->unsignedInteger('stock_iss_cust_id')->nullable();
            $table->unsignedInteger('iss_block_id')->nullable();
            $table->string('booked_status')->default('N');
            $table->string('transfer_status')->default('N');
            $table->string('cnno_status')->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gms_cnno_stock');
    }
}
