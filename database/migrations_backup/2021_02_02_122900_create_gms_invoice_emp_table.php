<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsInvoiceEmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_invoice_emp', function (Blueprint $table) {
              $table->increments('id');
              $table->integer('invoice_no')->nullable();
              $table->string('del_agent_code')->nullable(); 
              $table->string('del_agent_type')->nullable();
              $table->string('del_code')->nullable();
              $table->integer('total_cnno')->nullable();
              $table->decimal('total',10,2)->nullable();
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
        Schema::dropIfExists('gms_invoice_emp');
    }
}
