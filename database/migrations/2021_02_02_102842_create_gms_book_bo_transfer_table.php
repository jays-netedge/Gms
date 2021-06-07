<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookBoTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_bo_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('iss_bo_id')->nullable();
            $table->unsignedInteger('iss_dest_bo_id')->nullable();
            $table->unsignedInteger('iss_cust_id')->nullable();
            $table->string('iss_cust_code')->nullable();
            $table->string('iss_type')->nullable();
            $table->string('cnno_start')->nullable();
            $table->string('cnno_end')->nullable();
            $table->string('office_ro')->nullable();
            $table->string('office_code')->nullable();
            $table->string('dest_office_code')->nullable();
            $table->string('description')->nullable();
            $table->string('tranfer_type')->nullable();
            $table->string('status')->nullable();
            $table->date('entry_date')->nullable();
            $table->date('recieved_date')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('gms_book_bo_transfer');
    }
}
