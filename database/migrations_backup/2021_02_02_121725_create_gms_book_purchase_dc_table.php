<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookPurchaseDcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_purchase_dc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dc_no')->nullable();
            $table->date('dc_date')->nullable();
            $table->string('purchase_no')->nullable();
            $table->integer('item_id')->nullable();
            $table->string('from_cnno')->nullable();
            $table->string('to_cnno')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('total_allotted')->nullable();
            $table->string('description')->default(0);
            $table->integer('status')->nullable();
            $table->date('posted_date')->nullable();
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
        Schema::dropIfExists('gms_book_purchase_dc');
    }
}
