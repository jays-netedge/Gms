<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_invoice', function (Blueprint $table) {
                 $table->increments('id');
                  $table->unsignedInteger('s_id')->nullable();
                  $table->unsignedInteger('user_id')->nullable(); 
                  $table->string('invoice_invoice_no')->nullable(); 
                  $table->date('invoice_invoice_date')->nullable(); 
                  $table->string('po_no')->nullable(); 
                  $table->string('po_date')->nullable(); 
                  $table->string('contact_person')->nullable();
                  $table->string('contact_person_ph')->nullable();
                  $table->string('transport_person_name')->nullable(); 
                  $table->string('transport_lr_no')->nullable(); 
                  $table->string('transport_no_parcel')->nullable(); 
                  $table->string('esugun_no')->nullable(); 
                  $table->string('from_address')->nullable();
                  $table->string('to_address')->nullable();
                  $table->string('basic_value')->nullable();
                  $table->string('amount_paid')->nullable(); 
                  $table->string('tax_type')->nullable();
                  $table->string('tax_percentage')->nullable(); 
                  $table->string('tax_amount')->nullable(); 
                  $table->string('extra_rate_id')->nullable(); 
                  $table->string('extra_rate_amount')->nullable(); 
                  $table->string('others')->nullable();
                  $table->string('grand_total')->nullable(); 
                  $table->integer('payment_terms')->nullable(); 
                  $table->string('terms')->nullable();
                  $table->integer('status')->nullable();
                  $table->string('account_type')->nullable(); 
                  $table->integer('invoice_dc')->nullable(); 
                  $table->date('invoice_dc_date')->nullable(); 
                  $table->string('invoice_dc_description')->nullable();
                  $table->unsignedInteger('proforma_id')->nullable(); 
                  $table->date('proforma_date')->nullable(); 
                  $table->integer('type')->nullable(); 
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
        Schema::dropIfExists('inv_invoice');
    }
}
