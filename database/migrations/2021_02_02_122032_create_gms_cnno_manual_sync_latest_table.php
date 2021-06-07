<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCnnoManualSyncLatestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_cnno_manual_sync_latest', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cnno')->nullable();
            $table->enum('booking',['0','1'])->default(0);
            $table->enum('dmf',['0','1','2'])->default(0);
            $table->enum('pmf',['0','1','2'])->default(0);
            $table->enum('exr',['0','1','2'])->default(0);
            $table->dateTime('booking_updated_date_time')->nullable();
            $table->dateTime('dmf_updated_date_time')->nullable();
            $table->dateTime('pmf_updated_date_time')->nullable();
            $table->dateTime('exr_updated_date_time')->nullable();
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
        Schema::dropIfExists('gms_cnno_manual_sync_latest');
    }
}
