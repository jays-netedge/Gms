<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsExrDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_exr_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('exr_no')->nullable();
            $table->string('exr_type')->nullable();
            $table->date('exr_date')->nullable();
            $table->time('exr_time')->nullable();
            $table->string('exr_cnno')->nullable();
            $table->decimal('exr_wt',8,3)->nullable();
            $table->decimal('exr_vol_wt',8,3)->nullable();
            $table->string('exr_remarks')->nullable();
            $table->string('exr_origin_branch')->nullable();
            $table->string('exr_origin_ro')->nullable();
            $table->string('exr_receieved_emp')->nullable();
            $table->string('exr_received_by')->nullable();
            $table->string('exr_recevied_ro')->nullable();
            $table->dateTime('exr_received_date')->nullable();
            $table->integer('exr_received_type')->nullable();
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
        Schema::dropIfExists('gms_exr_dtls');
    }
}
