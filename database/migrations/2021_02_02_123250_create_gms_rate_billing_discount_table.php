<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateBillingDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_billing_discount', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('max_no')->nullable(); 
            $table->string('billing_rate_code')->nullable();
            $table->string('billing_type')->nullable();
            $table->string('delivery_type')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('rate_per_cnno',7,2)->nullable();
            $table->decimal('rate_per_weight',7,2)->nullable();
            $table->string('invoice_value_range')->nullable();
            $table->string('invoice_value_percentage')->nullable();
            $table->string('flat_rate')->default('N');
            $table->string('slab_rate')->default('Y');
            $table->string('from_wt')->nullable();
            $table->string('to_wt')->nullable();
            $table->string('rate')->nullable();
            $table->string('addnl')->default('N');
            $table->string('addnl_wt')->nullable();
            $table->string('addnl_rate')->nullable();
            $table->string('non_from_wt')->nullable();
            $table->string('non_to_wt')->nullable();
            $table->string('non_rate')->nullable();
            $table->string('non_addnl')->default('N');
            $table->string('non_addnl_wt')->nullable();
            $table->string('non_addnl_rate')->nullable();
            $table->date('created_date')->nullable();
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('gms_rate_billing_discount');
    }
}
