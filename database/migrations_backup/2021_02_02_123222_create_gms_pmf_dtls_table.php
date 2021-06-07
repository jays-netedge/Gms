<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsPmfDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_pmf_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pmf_no')->nullable(); 
            $table->integer('max_no')->nullable();
            $table->string('pmf_type')->nullable();
            $table->date('pmf_date')->nullable();
            $table->time('pmf_time')->nullable();
            $table->string('pmf_emp_code')->nullable();
            $table->string('pmf_origin')->nullable();
            $table->string('pmf_dest')->nullable();
            $table->string('pmf_mode')->nullable();
            $table->string('pmf_doc')->nullable();
            $table->decimal('pmf_amt',10,2)->nullable();
            $table->decimal('pmf_srno',3,0)->nullable();
            $table->string('pmf_cnno')->nullable();
            $table->string('pmf_cnno_type')->nullable();
            $table->decimal('pmf_wt',10,3)->nullable();
            $table->decimal('pmf_vol_wt',10,3)->nullable();
            $table->decimal('pmf_actual_wt',10,3)->nullable();
            $table->decimal('pmf_received_wt',10,3)->nullable();
            $table->decimal('pmf_vol_received_wt',10,3)->nullable();
            $table->decimal('pmf_actual_received_wt',10,3)->nullable();
            $table->string('pmf_pcs')->nullable();
            $table->string('pmf_pin')->nullable();
            $table->string('pmf_city')->nullable();
            $table->string('pmf_remarks')->nullable();
            $table->dateTime('pmf_entry_date')->nullable();
            $table->string('pmf_status')->nullable();
            $table->string('pmf_receieved_emp')->nullable();
            $table->string('pmf_received_by')->nullable();
            $table->dateTime('pmf_received_date')->nullable();
            $table->integer('pmf_recieved_type')->nullable();
            $table->string('pmf_ro')->nullable();
            $table->string('pmf_dest_ro')->nullable();
            $table->string('pmf_recevied_ro')->nullable();
            $table->string('pmf_cd_no')->nullable();
            $table->integer('pmf_misroute')->nullable();
            $table->string('changed_direct_emp')->nullable();
            $table->string('changed_original_dest_location')->nullable();
            $table->string('userid')->nullable();
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
        Schema::dropIfExists('gms_pmf_dtls');
    }
}
