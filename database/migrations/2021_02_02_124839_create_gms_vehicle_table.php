<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_vehicle', function (Blueprint $table) {
               $table->increments('id');
               $table->string('veh_no')->nullable();
               $table->string('veh_made')->nullable();
               $table->string('veh_provider')->nullable();
               $table->string('veh_remarks')->nullable();
               $table->string('status')->nullable();
               $table->dateTime('entry_date')->nullable();
               $table->unsignedInteger('user_id')->nullable();
               $table->string('susid')->nullable();
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
        Schema::dropIfExists('gms_vehicle');
    }
}
