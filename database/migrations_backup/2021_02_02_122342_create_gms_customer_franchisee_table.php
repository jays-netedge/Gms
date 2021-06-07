<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCustomerFranchiseeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_customer_franchisee', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fran_cust_inc')->nullable();
            $table->string('cust_code')->nullable();
            $table->string('fran_cust_code')->nullable();
            $table->string('fran_cust_city')->nullable();
            $table->string('fran_cust_name')->nullable();
            $table->string('fran_cust_email')->nullable();
            $table->string('created_branch')->nullable();
            $table->string('rate_card_status')->nullable();
            $table->date('entry_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('gms_customer_franchisee');
    }
}
