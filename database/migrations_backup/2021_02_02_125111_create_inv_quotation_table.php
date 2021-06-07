<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_quotation', function (Blueprint $table) {
                  $table->increments('id');
                  $table->unsignedInteger('s_id')->nullable();
                  $table->unsignedInteger('user_id')->nullable(); 
                  $table->string('quotation_invoice_no')->nullable(); 
                  $table->date('quotation_invoice_date')->nullable(); 
                  $table->string('from_address')->nullable(); 
                  $table->string('to_address')->nullable(); 
                  $table->string('basic_value')->nullable();
                  $table->decimal('amount_paid',10,2)->nullable();
                  $table->string('tax_type')->nullable();
                  $table->string('tax_percentage')->nullable();
                  $table->string('tax_amount')->nullable();
                  $table->string('others')->nullable();
                  $table->string('grand_total')->nullable();
                  $table->string('terms')->nullable();
                  $table->integer('status')->nullable();
                  $table->integer('account_type')->nullable();
                  $table->date('date')->nullable();
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
        Schema::dropIfExists('inv_quotation');
    }
}
