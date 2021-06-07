<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsDmfDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_dmf_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dmf_type')->nullable();
            $table->string('dmf_fr_code')->nullable();
            $table->string('dmf_branch')->nullable();
            $table->string('dmf_emp')->nullable();
            $table->string('dmf_ref_no')->nullable();
            $table->string('dmf_mfno')->nullable();
            $table->integer('max_no')->nullable();
            $table->date('dmf_mfdate')->nullable();
            $table->time('dmf_mftime')->nullable();
            $table->decimal('dmf_srno',3,0)->nullable();
            $table->string('dmf_cnno')->nullable();
            $table->string('dmf_cnno_current_status')->default('WTD');
            $table->string('dmf_cnno_type')->nullable('R');
            $table->integer('dmf_pin')->nullable();
            $table->string('dmf_dest')->nullable();
            $table->decimal('dmf_wt',7,3)->nullable();
            $table->string('mf_pmfno')->nullable();
            $table->integer('dmf_pcs')->nullable();
            $table->decimal('dmf_delv_amt',8,2)->nullable();
            $table->string('dmf_consgn')->nullable();
            $table->string('dmf_consgn_add')->nullable();
            $table->string('dmf_cn_status')->nullable();
            $table->string('dmf_drsno')->nullable();
            $table->string('dl_name')->nullable();
            $table->string('dl_relationship')->nullable();
            $table->string('dl_mobile')->nullable();
            $table->string('dl_phone')->nullable();
            $table->string('dl_signature')->nullable();
            $table->string('dl_c_signature')->nullable();
            $table->string('dl_pay_chash')->nullable();
            $table->string('dl_pay_cheque')->nullable();
            $table->string('dl_pay_dd')->nullable();
            $table->decimal('dl_chash_amt',7,2)->nullable();
            $table->string('dl_cheque_bank_name')->nullable();
            $table->string('dl_cheque_no')->nullable();
            $table->decimal('dl_cheque_amt',7,2)->nullable();
            $table->string('dl_dd_bank_name')->nullable();
            $table->string('dl_dd_no')->nullable();
            $table->decimal('dl_dd_amt',7,2)->nullable();
            $table->date('dmf_atmpt_date')->nullable();
            $table->time('dmf_atmpt_time')->nullable();
            $table->string('dmf_ndel_reason')->nullable();
            $table->string('dmf_remarks')->nullable();
            $table->string('dmf_cnno_remarks')->nullable();
            $table->string('dmf_delv_remarks')->nullable();
            $table->integer('dmf_pod_status')->nullable();
            $table->string('dmf_cd_no')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->dateTime('dmf_actual_date')->nullable();
            $table->dateTime('modify_date')->nullable();
            $table->string('modified_by')->nullable();
            $table->integer('dmf_invoice_no')->nullable();
            $table->integer('dmf_delivery_t')->nullable();
            $table->integer('status')->nullable();
            $table->string('userid')->nullable();
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
        Schema::dropIfExists('gms_dmf_dtls');
    }
}
