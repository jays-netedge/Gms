<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAryanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aryan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('book_br_code')->nullable();
            $table->string('book_emp_code')->nullable();
            $table->string('book_cust_type')->nullable();
            $table->string('book_cust_code')->nullable();
            $table->string('book_mfno')->nullable();
            $table->decimal('book_mfdate', 4, 0)->nullable();
            $table->string('book_mfrefno')->nullable();
            $table->string('book_srno')->nullable();
            $table->string('book_cnno')->nullable();
            $table->string('book_refno')->nullable();
            $table->string('book_weight')->nullable();
            $table->string('book_vol_weight')->nullable();
            $table->string('book_vol_lenght')->nullable();
            $table->string('book_vol_breight')->nullable();
            $table->string('book_vol_height')->nullable();
            $table->integer('book_pcs')->nullable();
            $table->decimal('book_pin',6, 0)->nullable();
            $table->string('book_org')->nullable();
            $table->string('book_dest')->nullable();
            $table->string('book_location')->nullable();
            $table->string('book_product_type')->nullable();
            $table->string('book_mode')->nullable();
            $table->string('book_doc')->nullable();
            $table->string('book_service_type')->nullable();
            $table->string('book_cons_addr')->nullable();
            $table->string('book_cons_dtl')->nullable();
            $table->string('book_cons_mobile')->nullable();
            $table->string('book_cn_dtl')->nullable();
            $table->string('book_cn_mobile')->nullable();
            $table->string('book_cn_email')->nullable();
            $table->string('book_agent')->nullable();
            $table->string('book_remarks')->nullable();
            $table->decimal('book_cod',10,2)->nullable();
            $table->decimal('book_topay',10,2)->nullable();
            $table->decimal('book_topay_inv',10,2)->nullable();
            $table->decimal('book_billamt',8,2)->nullable();
            $table->string('book_invno')->nullable();
            $table->date('book_invdate')->nullable();
            $table->decimal('book_oda_perwt',7,2)->nullable();
            $table->decimal('book_oda_rate',7,2)->nullable();
            $table->decimal('book_mps_rate',7,2)->nullable();
            $table->decimal('book_mps_inv',7,2)->nullable();
            $table->decimal('book_fov_rate',7,2)->nullable();
            $table->decimal('book_fov_inv',7,2)->nullable();
            $table->decimal('book_fvo_rate',7,2)->nullable();
            $table->decimal('book_fvo_inv',7,2)->nullable();
            $table->decimal('book_isc_rate',7,2)->nullable();
            $table->decimal('book_isc_inv',7,2)->nullable();
            $table->string('book_nsl_stype')->nullable();
            $table->decimal('book_nsl_rate',10,2)->nullable();
            $table->string('book_scan_doc')->nullable();
            $table->string('book_pod_scan')->nullable();
            $table->decimal('book_total_amount',7,2)->nullable();
            $table->string('book_current_status')->nullable();
            $table->string('bill_cust')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->string('status')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('sysid')->nullable();
            $table->string('bill_status')->nullable();
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
        Schema::dropIfExists('aryan');
    }
}
