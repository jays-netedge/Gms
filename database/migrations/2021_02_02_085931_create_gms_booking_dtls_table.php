<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookingDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_booking_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('book_type')->nullble();
            $table->string('book_br_code')->nullble();
            $table->string('book_emp_code')->nullable();
            $table->string('book_cust_type')->nullble();
            $table->string('book_cust_type_orginal')->nullble();
            $table->string('book_cust_code')->nullble();
            $table->string('book_fr_cust_code')->nullble();
            $table->string('book_mfno')->nullble();
            $table->integer('max_no')->nullble();
            $table->date('book_mfdate')->nullble();
            $table->time('book_mftime')->nullble();
            $table->time('book_mftime1')->nullble();
            $table->string('book_mfrefno')->nullble();
            $table->decimal('book_srno', 3, 0)->default(0);
            $table->string('book_cnno')->nullable();
            $table->string('book_refno')->nullable();
            $table->decimal('book_weight', 8, 3)->default(0);
            $table->decimal('book_vol_weight', 8, 3)->default(0);
            $table->string('book_vol_lenght')->nullable();
            $table->string('book_vol_breight')->nullable();
            $table->string('book_vol_height')->nullable();
            $table->integer('book_pcs')->nullable();
            $table->decimal('book_pin', 6, 0)->default(0);
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
            $table->string('book_cons_email')->nullable();
            $table->string('book_cn_name')->nullable();
            $table->string('book_cn_dtl')->nullable();
            $table->string('book_cn_mobile')->nullable();
            //$table->string('book_cn_name')->nullable();
            $table->string('book_cn_email')->nullable();
            $table->string('book_agent')->nullable();
            $table->string('book_remarks')->nullable();
            $table->integer('book_rate_id')->nullable();
            $table->decimal('book_cod', 10, 2)->default(0);
            $table->decimal('book_topay', 10, 2)->default(0);
            $table->decimal('book_topay_inv', 10, 2)->nullable();
            $table->decimal('book_billamt', 20, 2)->nullable();
            $table->string('book_invno')->nullable();
            $table->date('book_invdate')->nullable();
            $table->decimal('book_oda_perwt', 7, 2)->nullable();
            $table->decimal('book_oda_rate', 7, 2)->nullable();
            $table->decimal('book_mps_rate', 7, 2)->nullable();
            $table->decimal('book_mps_inv', 7, 2)->nullable();
            $table->decimal('book_fov_rate', 7, 2)->nullable();
            $table->decimal('book_fov_inv', 7, 2)->nullable();
            $table->decimal('book_fvo_rate', 7, 2)->nullable();
            $table->decimal('book_fvo_inv', 7, 2)->nullable();
            $table->decimal('book_isc_rate', 7, 2)->nullable();
            $table->decimal('book_isc_inv', 7, 2)->nullable();
            $table->string('book_nsl_stype')->nullable();
            $table->decimal('book_nsl_rate', 10, 2)->nullable();
            $table->decimal('book_edl_perwt', 10, 2)->nullable();
            $table->decimal('book_edl_rate', 10, 2)->nullable();
            $table->string('book_scan_doc')->nullable();
            $table->string('book_pod_scan')->nullable();
            $table->string('book_pod_scan_office')->nullable();
            $table->string('book_pod_scan_emp')->nullable();
            $table->dateTime('book_pod_scan_date')->nullable();
            $table->string('bulk_flag')->nullable();
            $table->decimal('book_total_amount', 20, 2)->nullable();
            $table->string('book_current_status')->nullable();
            $table->string('book_temp_office')->nullable();
            $table->string('book_temp_emp')->nullable();
            $table->string('booking_type')->nullable();
            $table->string('bill_cust')->nullable();
            $table->string('invoice_no')->nullable();
            $table->integer('delivery_t')->nullable();
            $table->string('delivery_t_remarks')->nullable();
            $table->dateTime('delivery_t_date')->nullable();
            $table->string('delivery_status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->string('status')->nullable();
            $table->string('user_id')->nullable();
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
        Schema::dropIfExists('gms_booking_dtls');
    }
}
