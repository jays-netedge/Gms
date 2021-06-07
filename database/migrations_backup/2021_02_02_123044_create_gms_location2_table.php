<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsLocation2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_location2', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city_code')->nullable();
            $table->string('location_name')->nullable(); 
            $table->string('pincode_value')->nullable();
            $table->string('office_type')->nullable();
            $table->string('city_under')->nullable();
            $table->string('service')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('gms_location2');
    }
}
