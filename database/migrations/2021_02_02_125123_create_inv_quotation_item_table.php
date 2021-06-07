<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvQuotationItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_quotation_item', function (Blueprint $table) {
                  $table->increments('id');
                  $table->unsignedInteger('quotation_id')->nullable();
                  $table->unsignedInteger('sub_cat_id')->nullable(); 
                  $table->integer('products_id')->nullable(); 
                  $table->string('item_description')->nullable(); 
                  $table->decimal('item_cost',10,2)->nullable(); 
                  $table->integer('item_quantity')->nullable(); 
                  $table->integer('tax_type')->nullable();
                  $table->string('tax_percentage')->nullable();
                  $table->string('tax_amount')->nullable();
                  $table->date('posted_date')->nullable();
                  $table->timestamps();
                   $table->integer('is_deleted')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_quotation_item');
    }
}
