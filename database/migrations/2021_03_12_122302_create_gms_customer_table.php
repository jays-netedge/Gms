<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_customer', function (Blueprint $table) {
          $table->increments('id');
          $table->string('cust_type',100)->nullable();
          $table->string('cust_reach',100)->nullable();
          $table->string('cust_code',100)->nullable();
          $table->integer('cust_num')->nullable();
          $table->string('cust_la_ent',100)->nullable();
          $table->string('cust_location',100)->nullable(); 
          $table->string('cust_ent',100)->nullable();
          $table->string('cust_account_type',100)->nullable(); 
          $table->string('cust_la_address',100)->nullable();
          $table->string('cust_la_pan',100)->nullable();
          $table->string('cust_la_servicetax',100)->nullable();
          $table->string('cust_la_cin',100)->nullable();
          $table->date('cust_la_cindate')->nullable();
          $table->string('cust_name',100)->nullable();
          $table->date('cust_dob')->nullable();
          $table->string('cust_education',100)->nullable(); 
          $table->string('cust_qualification',100)->nullable(); 
          $table->string('cust_residen_address',100)->nullable();
          $table->string('cust_fat_wife_name',100)->nullable();
          $table->string('cust_pan',100)->nullable(); 
          $table->string('cust_cin',100)->nullable(); 
          $table->string('cust_phone',100)->nullable(); 
          $table->string('cust_email',100)->nullable(); 
          $table->string('cust_fax',100)->nullable(); 
          $table->string('cust_telno',100)->nullable(); 
          $table->string('cust_cp_name',100)->nullable(); 
          $table->string('cust_cp_telno',100)->nullable(); 
          $table->string('cust_cp_pan',100)->nullable();
          $table->string('cust_cp_taxno',100)->nullable(); 
          $table->string('cust_cp_vattinno',100)->nullable();
          $table->string('cust_cp_exciseno',100)->nullable(); 
          $table->string('cust_name1',100)->nullable(); 
          $table->date('cust_dob1')->nullable(); 
          $table->string('cust_secdip_fixed')->nullable();
          $table->string('cust_secdip_paid')->nullable();
          $table->string('cust_sec_chequeno')->nullable();
          $table->string('cust_education1',100)->nullable(); 
          $table->string('cust_qualification1',100)->nullable(); 
          $table->string('cust_residen_address1',100)->nullable(); 
          $table->string('cust_fat_wife_name1',100)->nullable(); 
          $table->string('cust_pan1',100)->nullable(); 
          $table->string('cust_cin1',100)->nullable(); 
          $table->string('cust_phone1',100)->nullable(); 
          $table->string('cust_email1',100)->nullable(); 
          $table->string('cust_cd_contact_name',100)->nullable(); 
          $table->string('cust_cd_designation',100)->nullable();
          $table->string('cust_cd_telno')->nullable(); 
          $table->string('cust_cd_email')->nullable(); 
          $table->string('cust_cd_mkt_exec')->nullable(); 
          $table->string('cust_cd_contractno')->nullable(); 
          $table->date('cust_cd_contract_date')->nullable(); 
          $table->date('cust_cd_renewal_date')->nullable(); 
          $table->date('cust_cd_exp_date')->nullable(); 
          $table->date('cust_cd_reg_date')->nullable();
          $table->string('cust_cd_rate_code')->nullable(); 
          $table->string('cust_cd_discount')->nullable();
          $table->string('cust_cd_closed')->nullable(); 
          $table->string('cust_cd_closing_date')->nullable();
          $table->string('cust_cd_remarks')->nullable(); 
          $table->decimal('cust_sd_fixed',9,2)->nullable(); 
          $table->string('cust_pb_nature')->nullable();
          $table->string('cust_pb_empdeployed')->nullable(); 
          $table->string('cust_pb_vehdeployed')->nullable(); 
          $table->string('cust_pb_turnover')->nullable();
          $table->string('cust_ad_bank_name')->nullable(); 
          $table->string('cust_ad_bank_branch')->nullable();
          $table->string('cust_ad_account_no')->nullable(); 
          $table->string('cust_ad_ifsc_code')->nullable(); 
          $table->string('cust_br_name')->nullable(); 
          $table->string('cust_br_address')->nullable();
          $table->string('cust_br_contact')->nullable(); 
          $table->string('cust_br_name1')->nullable(); 
          $table->string('cust_br_address1')->nullable();
          $table->string('cust_br_contact1')->nullable();
          $table->string('pan_card')->nullable();
          $table->string('passport_copy')->nullable();
          $table->string('driving_license')->nullable(); 
          $table->string('st_reg_certficate')->nullable(); 
          $table->string('aadhaar_card')->nullable(); 
          $table->string('voter_id')->nullable(); 
          $table->string('telephone_bill')->nullable(); 
          $table->string('photo')->nullable(); 
          $table->string('gallery_photo')->nullable(); 
          $table->string('gallery_photo1')->nullable(); 
          $table->string('gallery_photo2')->nullable();
          $table->string('cust_ro')->nullable(); 
          $table->string('cust_city')->nullable(); 
          $table->string( 'cust_actual_city')->nullable(); 
          $table->string('pincode_value')->nullable(); 
          $table->string('service_courier')->nullable(); 
          $table->string('service_logistics')->nullable();
          $table->string('service_gold')->nullable(); 
          $table->string('service_intracity')->nullable();
          $table->string('service_international')->nullable();
          $table->string('service_reverse_booking')->nullable();
          $table->string('multi_region')->nullable(); 
          $table->string('sms_status')->nullable(); 
          $table->string('email_status')->nullable(); 
          $table->string('cust_bill_right')->nullable(); 
          $table->string('cust_sf_reporting')->nullable(); 
          $table->string('sf_from_date')->nullable();
          $table->date('sf_to_date')->nullable(); 
          $table->dateTime('sf_last_updated')->nullable(); 
          $table->string('sf_discount_status')->nullable();
          $table->string('cust_rep_office')->nullable();
          $table->string('created_office_code')->nullable();
          $table->string('created_office_ro')->nullable(); 
          $table->integer('scheme_rate_id')->nullable(); 
          $table->string('delivery_code')->nullable(); 
          $table->integer('delivery_branch_status')->nullable(); 
          $table->string('discount_code')->nullable();
          $table->integer('monthly_bill_type')->nullable();
          $table->date('date_of_bill')->nullable();
          $table->integer('approved_status')->nullable(); 
          $table->date('entry_date')->nullable(); 
          $table->date('update_date')->nullable(); 
          $table->unsignedInteger('user_id')->nullable(); 
          $table->integer('sysid')->nullable(); 
          $table->string('gst_applicable')->nullable(); 
          $table->string('gst_number')->nullable(); 
          $table->string('gst_type')->nullable();
          $table->integer('is_deleted')->default('0');
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
        Schema::dropIfExists('gms_customer');
    }
}
