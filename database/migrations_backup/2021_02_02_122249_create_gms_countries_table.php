<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('countries_name')->nullable();
            $table->string('countries_iso_code_2')->nullable();
            $table->string('countries_iso_code_3')->default('Y');
            $table->integer('address_format_id')->nullable();
            $table->integer('status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('gms_countries');
    }
}
