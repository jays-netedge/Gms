<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookRoTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_ro_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('iss_ro_id')->nullable();
            $table->unsignedInteger('iss_dest_ro_id')->nullable();
            $table->string('cnno_start')->nullable();
            $table->string('cnno_end')->nullable();
            $table->string('office_code')->nullable();
            $table->string('dest_office_code')->nullable();
            $table->string('description',200)->nullable();
            $table->string('tranfer_type')->nullable();
            $table->string('status')->default('N');
            $table->dateTime('entry_date')->nullable();
            $table->dateTime('recieved_date')->nullable();
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
        Schema::dropIfExists('gms_book_ro_transfer');
    }
}
