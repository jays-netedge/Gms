<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookZonalRangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_zonal_range', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iss_zone')->nullable();
            $table->string('cnno_start')->nullable();
            $table->string('cnno_end')->nullable();
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
        Schema::dropIfExists('gms_book_zonal_range');
    }
}
