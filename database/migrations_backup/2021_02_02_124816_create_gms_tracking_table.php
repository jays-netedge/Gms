<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_tracking', function (Blueprint $table) {
               $table->increments('id');
               $table->string('TYPE')->nullable();
               $table->integer('CNNO')->nullable();
               $table->string('REFNO')->nullable();
               $table->string('MFNO')->nullable();
               $table->string('ORG')->nullable();
               $table->integer('DEST')->nullable();
               $table->string('WEIGHT')->nullable();
               $table->string('MODE')->nullable();
               $table->string('DOC_TYPE')->nullable();
               $table->string('CN_STATUS')->nullable();
               $table->string('REMARKS')->nullable();
               $table->date('DATE')->nullable();
               $table->integer('TIME')->nullable();
               $table->string('CUST_CODE')->nullable();
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
        Schema::dropIfExists('gms_tracking');
    }
}
