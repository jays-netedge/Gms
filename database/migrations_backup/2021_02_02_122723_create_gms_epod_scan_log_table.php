<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsEpodScanLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_epod_scan_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref_no')->nullable();
            $table->string('scanned_by')->nullable();
            $table->string('user_type')->nullable();
            $table->dateTime('scaned_date_time')->nullable();
            $table->string('cust_code')->nullable();
            $table->string('ip_address')->nullable();
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
        Schema::dropIfExists('gms_epod_scan_log');
    }
}
