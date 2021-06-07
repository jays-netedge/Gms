<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_payment', function (Blueprint $table) {
              $table->increments('id');
              $table->integer('type')->nullable(); 
              $table->string('invoice_receipt')->nullable();
              $table->integer('reference_id')->nullable();
              $table->string('cust_code')->nullable();
              $table->decimal('amount',10,2)->nullable();
              $table->integer('paid_through')->nullable();
              $table->string('bank_name')->nullable();
              $table->string('check_no')->nullable();
              $table->string('check_date')->nullable();
              $table->string('deposit_DD')->nullable();
              $table->date('date')->nullable();
              $table->string('description')->nullable();
              $table->integer('status')->nullable();
              $table->string('cust_ro')->nullable();
              $table->string('created_office')->nullable();
              $table->date('posted_date')->nullable();
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
        Schema::dropIfExists('gms_payment');
    }
}
