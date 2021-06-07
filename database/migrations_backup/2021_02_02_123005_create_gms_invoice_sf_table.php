<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsInvoiceSfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_invoice_sf', function (Blueprint $table) {
              $table->increments('id');
              $table->integer('invoice_no')->nullable();
              $table->string('customer_code')->nullable(); 
              $table->string('customer_type')->nullable();
              $table->integer('reg_booking')->nullable();
              $table->decimal('reg_amt',10,2)->nullable();
              $table->string('direct_booking')->nullable();
              $table->decimal('direct_amt',10,2)->nullable();
              $table->integer('total_cnno')->nullable();
              $table->decimal('total_amt',10,2)->nullable();
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
        Schema::dropIfExists('gms_invoice_sf');
    }
}
