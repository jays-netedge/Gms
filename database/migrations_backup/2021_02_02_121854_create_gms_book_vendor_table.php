<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_vendor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vendor_code')->nullable();
            $table->string('company')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('person')->nullable();
            $table->string('con_num1')->nullable();
            $table->string('con_num2')->nullable();
            $table->string('email')->nullable();
            $table->string('email1')->nullable();
            $table->string('fax')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->dateTime('posted_date')->nullable();
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
        Schema::dropIfExists('gms_book_vendor');
    }
}
