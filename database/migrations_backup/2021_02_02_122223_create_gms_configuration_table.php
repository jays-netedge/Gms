<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_configuration', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('log_no')->nullable(0);
            $table->string('configuration_tittle')->nullable();
            $table->string('configuration_egg')->nullable();
            $table->string('configuration_key')->nullable();
            $table->string('configuration_value')->nullable();
            $table->integer('sort_order')->nullable();
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
        Schema::dropIfExists('gms_configuration');
    }
}
