<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_code')->nullable(); 
            $table->string('product_name')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('sysid')->nullable();
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
        Schema::dropIfExists('gms_product');
    }
}
