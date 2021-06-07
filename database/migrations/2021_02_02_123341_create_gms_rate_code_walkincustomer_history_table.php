<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateCodeWalkincustomerHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_code_walkincustomer_history', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('description')->nullable();
            $table->integer('fuel_type')->nullable();
            $table->decimal('flat_fuel_percentage',7,2)->nullable();
            $table->string('slab_fuel_id')->nullable();
            $table->string('slab_fuel_from')->nullable();
            $table->string('slab_fuel_to')->nullable();
            $table->string('slab_fuel_percentage')->nullable();
            $table->date('effect_date_from')->nullable();
            $table->date('effect_date_to')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->string('user_id')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('unique_no')->nullable();
            $table->timestamps(); $table->integer('is_deleted')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gms_rate_code_walkincustomer_history');
    }
}
