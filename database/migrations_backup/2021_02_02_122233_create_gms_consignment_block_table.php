<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsConsignmentBlockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_consignment_block', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cnno_block')->nullable();
            $table->string('description',200)->nullable();
            $table->string('status')->default('Y');
            $table->integer('created_by')->nullable();
            $table->dateTime('entry_date')->nullable();
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
        Schema::dropIfExists('gms_consignment_block');
    }
}
