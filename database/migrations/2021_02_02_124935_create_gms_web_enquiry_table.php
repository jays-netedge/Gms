<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsWebEnquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_web_enquiry', function (Blueprint $table) {
               $table->increments('id');
               $table->string('enquiry_name')->nullable();
               $table->string('enquiry_company')->nullable();
               $table->string('enquiry_address')->nullable();
               $table->string('enquiry_district')->nullable();
               $table->string('enquiry_pincode')->nullable();
               $table->string('enquiry_country')->nullable();
               $table->string('enquiry_state')->nullable();
               $table->string('enquiry_city')->nullable();
               $table->string('enquiry_tel_no')->nullable();
               $table->string('enquiry_mobile_no')->nullable();
               $table->string('enquiry_fax_no')->nullable();
               $table->string('enquiry_email_id')->nullable();
               $table->string('enquiry_known_us')->nullable();
               $table->string('enquiry_applicant_details')->nullable();
               $table->date('posted_date')->nullable();
               $table->integer('enquiry_type')->nullable();
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
        Schema::dropIfExists('gms_web_enquiry');
    }
}
