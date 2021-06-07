<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCustomer2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_customer2', function (Blueprint $table) {
          $table->increments('id');
          $table->string('cust_id')->nullable(); 
          $table->string('cust_code')->nullable(); 
          $table->string('cust_name')->nullable(); 
          $table->string('cust_type')->nullable();
          $table->string('cust_ent')->nullable(); 
          $table->string('cust_add1')->nullable(); 
          $table->string('cust_add2')->nullable(); 
          $table->string('cust_city')->nullable(); 
          $table->decimal('cust_pin',6,0)->nullable(); 
          $table->string('cust_phone')->nullable(); 
          $table->string('cust_fax')->nullable(); 
          $table->string('cust_email')->nullable(); 
          $table->string('cust_contact')->nullable(); 
          $table->string('cust_contractno')->nullable(); 
          $table->date('cust_contract_date')->nullable(); 
          $table->date('cust_renewal_date')->nullable(); 
          $table->date('cust_exp_date')->nullable(); 
          $table->string('cust_mkt_exec')->nullable();
          $table->string('cust_pan')->nullable(); 
          $table->string('cust_staxno')->nullable(); 
          $table->date('cust_stax_date')->nullable();
          $table->string('cust_discount')->nullable(); 
          $table->string('cust_closed')->nullable(); 
          $table->date('cust_closing_date')->nullable();
          $table->string('cust_remarks')->nullable(); 
          $table->decimal('cust_secdip_fixed',8,2)->nullable(); 
          $table->decimal('cust_secdip_paid',8,2)->nullable(); 
          $table->string('cust_sec_chequeno')->nullable(); 
          $table->date('cust_sec_chequedate')->nullable(); 
          $table->decimal('cust_royl_amt')->nullable(); 
          $table->date('cust_royl_date')->nullable(); 
          $table->string('cust_ro')->nullable(); 
          $table->string('cust_rep_office')->nullable(); 
          $table->string('cust_rate_code')->nullable(); 
          $table->string('cust_web_push')->nullable(); 
          $table->decimal('cust_oda_charges',8,2)->nullable();
          $table->string('satus')->nullable(); 
          $table->string('cust_reach')->nullable(); 
          $table->string('created_office_code')->nullable(); 
          $table->string('address_proof')->nullable(); 
          $table->string('pan_card')->nullable(); 
          $table->string('st_reg_certficate')->nullable(); 
          $table->string('photo')->nullable(); 
          $table->string('deposit_DD')->nullable(); 
          $table->unsignedInteger('scheme_rate_id')->nullable(); 
          $table->integer('approved_status')->nullable(); 
          $table->dateTime('entry_date')->nullable(); 
          $table->unsignedInteger('user_id')->nullable();
          $table->string('sysid')->nullable();
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
        Schema::dropIfExists('gms_customer2');
    }
}
