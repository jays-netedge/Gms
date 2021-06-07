<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_purchase', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('purchase_invoice_no')->nullable();
            $table->date('purchase_invoice_date')->nullable();
            $table->string('from_address')->nullable();
            $table->string('to_address')->nullable();
            $table->string('to_tin')->nullable();
            $table->string('basic_value')->nullable();
            $table->decimal('amount_paid',10,2)->nullable();
            $table->string('tax_type')->nullable();
            $table->string('tax_percentage')->nullable();
            $table->string('tax_amount')->nullable();
            $table->string('others')->nullable();
            $table->string('grand_total')->nullable();
            $table->string('terms')->nullable();
            $table->integer('book_cat_type')->nullable();
            $table->integer('status')->nullable();
            $table->integer('account_type')->nullable();
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
        Schema::dropIfExists('gms_book_purchase');
    }
}
