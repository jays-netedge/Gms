<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookEpodGenerateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_epod_generate', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cnn_no')->nullable();
            $table->unsignedInteger('iss_cust_id')->nullable();
            $table->string('unique_no')->nullable();
            $table->string('booking_branch')->nullable();
            $table->string('consignor_name')->nullable();
            $table->string('consignor_mobile')->nullable();
            $table->string('consignor_email')->nullable();
            $table->string('consignor_pincode')->nullable();
            $table->string('consignor_address')->nullable();
            $table->string('consignee_office_name')->nullable();
            $table->string('consignee_name')->nullable();
            $table->string('consignee_address')->nullable();
            $table->string('consignee_pincode')->nullable();
            $table->string('consignee_mobile')->nullable();
            $table->string('consignee_email')->nullable();
            $table->string('cust_code')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('doc_type')->nullable();
            $table->integer('no_pcs')->nullable();
            $table->decimal('value_declared',10,3)->nullable();
            $table->float('cod_value',12,2)->nullable();
            $table->string('cust_epod_remarks')->nullable();
            $table->integer('status')->nullable();
            $table->dateTime('entry_date')->nullable();
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
        Schema::dropIfExists('gms_book_epod_generate');
    }
}
