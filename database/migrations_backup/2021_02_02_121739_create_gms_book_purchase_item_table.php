<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookPurchaseItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_purchase_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_id')->nullable();
            $table->unsignedInteger('book_cat_id')->nullable();
            $table->string('from_range')->nullable();
            $table->string('to_range')->nullable();
            $table->integer('total_allotted')->nullable();
            $table->string('item_description')->nullable();
            $table->string('item_cost')->nullable();
            $table->integer('item_quantity')->nullable();
            $table->integer('tax_type')->nullable();
            $table->string('tax_percentage')->nullable();
            $table->string('tax_amount')->nullable();
            $table->integer('stock_status')->nullable();
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
        Schema::dropIfExists('gms_book_purchase_item');
    }
}
