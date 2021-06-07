<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsFuelChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_fuel_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('barrel_price_index')->nullable();
            $table->decimal('from_price',7,2)->nullable();
            $table->decimal('to_price',7,2)->nullable();
            $table->decimal('charged_percentage',7,2)->nullable();
            $table->date('posted_month')->nullable();
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
        Schema::dropIfExists('gms_fuel_charges');
    }
}
