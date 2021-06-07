<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_invoice', function (Blueprint $table) {
              $table->increments('id');
              $table->string('invoice_no')->nullable();
              $table->date('invoice_date')->nullable(); 
              $table->string('month')->nullable();
              $table->string('year')->nullable();
              $table->string('esugun_no')->nullable();
              $table->string('cust_type')->nullable();
              $table->string('branch_code')->nullable();
              $table->string('branch_ro')->nullable();
              $table->string('customer_code')->nullable(); 
              $table->string('from_address')->nullable(); 
              $table->string('to_address')->nullable(); 
              $table->date('from_date')->nullable(); 
              $table->date('to_date')->nullable(); 
              $table->integer('monthly_bill_type')->nullable();
              $table->string('ac_invoice_no')->nullable(); 
              $table->string('fr_invoice_no')->nullable(); 
              $table->date('fr_invoice_date')->nullable(); 
              $table->decimal('fr_actual_service_charge',10,2)->nullable(); 
              $table->decimal('fr_service_charge',10,2)->nullable(); 
              $table->decimal('fr_less_billing_discount',10,2)->nullable(); 
              $table->decimal('fr_net_service_charge',10,2)->nullable(); 
              $table->decimal('fr_fuel_percentage',10,2)->nullable(); 
              $table->decimal('fr_fuel_amount',10,2)->nullable(); 
              $table->decimal('fr_sub_total',10,2)->nullable(); 
              $table->decimal('fr_actual_less_delivery_discount',10,2)->nullable(); 
              $table->decimal('fr_less_delivery_discount',10,2)->nullable(); 
              $table->decimal('fr_actual_less_sf_discount',10,2)->nullable(); 
              $table->decimal('fr_less_sf_discount',10,2)->nullable(); 
              $table->decimal('fr_total',10,2)->nullable(); 
              $table->string('fr_service_tax_name')->nullable(); 
              $table->string('fr_service_tax_percentage')->nullable(); 
              $table->string('fr_service_tax_amount')->nullable(); 
              $table->decimal('fr_voucher_amount',10,2)->nullable(); 
              $table->decimal('fr_grand_total',10,2)->nullable(); 
              $table->decimal('total_weight',10,3)->nullable(); 
              $table->integer('total_cnno')->nullable(); 
              $table->string('basic_value')->nullable(); 
              $table->decimal('amount_paid',10,2)->nullable(); 
              $table->string('tax_type')->nullable(); 
              $table->string('tax_percentage')->nullable(); 
              $table->string('tax_amount')->nullable(); 
              $table->string('extra_rate_id')->nullable(); 
              $table->string('extra_rate_amount')->nullable(); 
              $table->string('others')->nullable(); 
              $table->string('grand_total')->nullable(); 
              $table->integer('payment_terms')->nullable(); 
              $table->string('terms')->nullable(); 
              $table->integer('print_type')->nullable(); 
              $table->integer('status')->nullable(); 
              $table->integer('account_type')->nullable(); 
              $table->string('invoice_dc')->nullable();
              $table->integer('invoice_edit_status')->nullable(); 
              $table->date('invoice_dc_date')->nullable(); 
              $table->string('invoice_dc_description')->nullable(); 
              $table->string('proforma_id')->nullable(); 
              $table->date('proforma_date')->nullable(); 
              $table->integer('type')->nullable(); 
              $table->integer('previous_bill')->nullable(); 
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
        Schema::dropIfExists('gms_invoice');
    }
}
