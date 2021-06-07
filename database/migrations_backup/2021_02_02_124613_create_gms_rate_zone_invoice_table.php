<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateZoneInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_zone_invoice', function (Blueprint $table) {
               $table->increments('id');
               $table->integer('ro_id')->nullable();
               $table->string('to_ro')->nullable();
               $table->string('month')->nullable();
               $table->string('year')->nullable();
               $table->string('zone_inv_type')->nullable();
               $table->string('zone_unique_no')->nullable();
               $table->date('posted_date')->nullable();
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
        Schema::dropIfExists('gms_rate_zone_invoice');
    }
}
