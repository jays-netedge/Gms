<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsNdelReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_ndel_reason', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ndel_code')->nullable();
            $table->string('ndel_desc')->nullable(); 
            $table->string('ndel_name')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->dateTime('update_date')->nullable();
            $table->string('user_id')->nullable();
            $table->string('sysid')->nullable();
            $table->string('charge_flg')->nullable();
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
        Schema::dropIfExists('gms_ndel_reason');
    }
}
