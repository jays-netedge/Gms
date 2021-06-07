<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCustomerContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_customer_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cust_code')->nullable();
            $table->string('cust_type')->nullable();
            $table->string('cust_name')->nullable();
            $table->date('cust_dob')->nullable();
            $table->string('cust_education')->nullable();
            $table->string('cust_qualification')->nullable();
            $table->string('cust_residen_address')->nullable();
            $table->string('cust_fat_wife_name')->nullable();
            $table->string('cust_pan')->nullable();
            $table->string('cust_cin')->nullable();
            $table->string('cust_phone')->nullable();
            $table->string('cust_email')->nullable();
            $table->string('cust_fax')->nullable();
            $table->string('cust_telno')->nullable();
            $table->string('cust_cp_name')->nullable();
            $table->string('cust_cp_telno')->nullable();
            $table->string('cust_cp_pan')->nullable();
            $table->string('cust_cp_taxno')->nullable();
            $table->string('cust_cp_vattinno')->nullable();
            $table->string('cust_cp_exciseno')->nullable();
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
        Schema::dropIfExists('gms_customer_contacts');
    }
}
