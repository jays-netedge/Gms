<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsOfficeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_office', function (Blueprint $table) {
              $table->increments('id');
              $table->string('office_code')->nullable(); 
              $table->string('office_name')->nullable();
              $table->string('office_type')->nullable();
              $table->string('branch_category')->nullable();
              $table->integer('office_under')->nullable();
              $table->string('office_flag')->nullable();
              $table->string('office_ent')->nullable();
              $table->string('office_add1')->nullable();
              $table->string('office_add2')->nullable();
              $table->string('office_city')->nullable(); 
              $table->decimal('office_pin',6,0)->nullable();
              $table->string('office_location')->nullable();
              $table->string('office_phone')->nullable();
              $table->string('office_fax')->nullable();
              $table->string('office_email')->nullable(); 
              $table->string('office_contact')->nullable(); 
              $table->string('office_contractno')->nullable();
              $table->date('office_contract_date')->nullable(); 
              $table->date('office_renewal_date')->nullable(); 
              $table->date('office_exp_date')->nullable();
              $table->string('office_sec_deposit')->nullable();
              $table->string('office_pan')->nullable();
              $table->string('office_stax_no')->nullable(); 
              $table->date('office_stax_date')->nullable(); 
              $table->string('office_bank_name')->nullable();
              $table->string('office_bank_branch_name')->nullable(); 
              $table->string('office_bank_accno')->nullable(); 
              $table->string('office_bank_ifsc')->nullable();
              $table->string('office_bank_micrno')->nullable(); 
              $table->string('office_bank_address')->nullable(); 
              $table->string('office_closed')->nullable();
              $table->string('office_closing_date')->nullable();
              $table->string('office_sf_flag')->nullable(); 
              $table->string('office_remarks')->nullable();
              $table->string('office_reporting')->nullable(); 
              $table->string('office_walkin')->nullable(); 
              $table->string('office_cnnogen')->nullable(); 
              $table->string('office_delt')->nullable(); 
              $table->string('status')->nullable();
              $table->integer('login_assigned')->nullable(); 
              $table->date('entry_date')->nullable(); 
              $table->date('update_date')->nullable();
              $table->unsignedInteger('user_id')->nullable(); 
              $table->string('sys_id')->nullable(); 
              $table->string('gst_tin')->nullable();
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
        Schema::dropIfExists('gms_office');
    }
}
