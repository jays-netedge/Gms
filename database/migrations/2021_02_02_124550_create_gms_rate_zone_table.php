<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_zone', function (Blueprint $table) {
               $table->increments('id');
               $table->integer('unique_zone_id')->nullable();
               $table->string('service_type')->nullable();
               $table->string('ro_code')->nullable();
               $table->string('mode')->nullable();
               $table->string('location_type')->nullable();
               $table->string('org')->nullable();
               $table->string('dest')->nullable();
               $table->string('via')->nullable();
               $table->string('rate_wt')->nullable();
               $table->string('rate_amt')->nullable();
               $table->string('add_rate_wt')->nullable();
               $table->string('add_rate_amt')->nullable();
               $table->string('status')->nullable();
               $table->integer('approved_status')->nullable();
               $table->dateTime('entry_date')->nullable();
               $table->string('unique_no')->nullable();
               $table->string('user_id')->nullable();
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
        Schema::dropIfExists('gms_rate_zone');
    }
}
