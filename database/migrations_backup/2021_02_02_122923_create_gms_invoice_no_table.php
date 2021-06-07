<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsInvoiceNoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_invoice_no', function (Blueprint $table) {
              $table->increments('id');
              $table->integer('branch_ro')->nullable();
              $table->integer('month')->nullable(); 
              $table->integer('year')->nullable();
              $table->integer('invoice_no')->nullable();
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
        Schema::dropIfExists('gms_invoice_no');
    }
}
