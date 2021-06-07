<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsFuelDefaultChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_fuel_default_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('fuel_price',7,2)->nullable();
            $table->date('fuel_date_from')->nullable();
            $table->date('fuel_date_to')->nullable();
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
        Schema::dropIfExists('gms_fuel_default_charges');
    }
}
