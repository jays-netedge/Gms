<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsInvoiceCustTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_invoice_cust', function (Blueprint $table) {
              $table->increments('id');
              $table->string('cust_invoice_no')->nullable();
              $table->date('cust_invoice_date')->nullable(); 
              $table->string('cust_code')->nullable();
              $table->string('fran_cust_code')->nullable();
              $table->date('from_date')->nullable();
              $table->date('to_date')->nullable();
              $table->string('invoice_status')->nullable();
              $table->date('date')->nullable();
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
        Schema::dropIfExists('gms_invoice_cust');
    }
}
