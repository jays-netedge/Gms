<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateZoneServiceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_zone_service_history', function (Blueprint $table) {
               $table->increments('id');
               $table->string('zone_service_type')->nullable();
               $table->string('zone_book_service')->nullable();
               $table->decimal('weight',7,3)->nullable();
               $table->decimal('rate',7,2)->nullable();
               $table->date('from_date')->nullable();
               $table->date('to_date')->nullable();
               $table->string('unique_no')->nullable();
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
        Schema::dropIfExists('gms_rate_zone_service_history');
    }
}
