<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvInvoiceItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_invoice_item', function (Blueprint $table) {
                  $table->increments('id');
                  $table->unsignedInteger('invoice_id')->nullable();
                  $table->unsignedInteger('dc_id')->nullable(); 
                  $table->unsignedInteger('pf_id')->nullable(); 
                  $table->unsignedInteger('sub_cat_id')->nullable(); 
                  $table->unsignedInteger('products_id')->nullable(); 
                  $table->string('item_description')->nullable(); 
                  $table->decimal('item_cost',10,2)->nullable();
                  $table->integer('item_quantity')->nullable();
                  $table->integer('tax_type')->nullable();
                  $table->string('tax_percentage')->nullable();
                  $table->integer('tax_amount')->nullable();
                  $table->integer('status')->nullable();
                  $table->dateTime('posted_date')->nullable();
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
        Schema::dropIfExists('inv_invoice_item');
    }
}
