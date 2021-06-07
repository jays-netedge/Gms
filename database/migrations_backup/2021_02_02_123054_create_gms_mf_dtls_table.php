<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsMfDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_mf_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mf_type')->nullable();
            $table->date('mf_date')->nullable(); 
            $table->time('mf_time')->nullable();
            $table->string('mf_emp_code')->nullable();
            $table->string('mf_origin_type')->nullable();
            $table->string('mf_origin')->nullable();
            $table->string('mf_dest_type')->nullable();
            $table->string('mf_dest')->nullable();
            $table->string('mf_mode')->nullable();
            $table->decimal('mf_srno',3,0)->nullable();
            $table->decimal('mf_wt',10,3)->nullable();
            $table->string('mf_vol_wt')->nullable();
            $table->decimal('mf_actual_wt',10,3)->nullable();
            $table->integer('mf_pcs')->nullable();
            $table->string('mf_pmf_dest')->nullable();
            $table->string('mf_remarks')->nullable();
            $table->dateTime('mf_entry_date')->nullable();
            $table->string('mf_status')->nullable();
            $table->string('mf_receieved_emp')->nullable();
            $table->string('mf_received_by')->nullable();
            $table->dateTime('mf_received_date')->nullable();
            $table->string('mf_transport_type')->nullable();
            $table->string('mf_ro')->nullable();
            $table->string('mf_dest_ro')->nullable();
            $table->string('mf_recevied_ro')->nullable();
            $table->string('mf_cd_no')->nullable();
            $table->integer('mf_misroute')->nullable();
            $table->string('changed_direct_emp')->nullable();
            $table->string('changed_original_dest_location')->nullable();
            $table->unsignedInteger('userid')->nullable();
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
        Schema::dropIfExists('gms_mf_dtls');
    }
}
