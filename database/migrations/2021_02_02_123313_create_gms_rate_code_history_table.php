<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateCodeHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_code_history', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('rate_code')->nullable();
            $table->string('rate_type_card')->nullable();
            $table->string('rate_type')->nullable();
            $table->string('rate_name')->nullable();
            $table->string('office_code')->nullable();
            $table->string('cust_code')->nullable();
            $table->string('description')->nullable();
            $table->integer('fuel_type')->nullable();
            $table->decimal('flat_fuel_percentage',7,2)->nullable();
            $table->string('slab_fuel_id')->nullable();
            $table->string('slab_fuel_from')->nullable();
            $table->string('slab_fuel_to')->nullable();
            $table->string('slab_fuel_percentage')->nullable();
            $table->string('docket_type')->nullable();
            $table->string('docket_dx')->nullable();
            $table->string('docket_nx')->nullable();
            $table->string('book_upto_weight')->nullable();
            $table->decimal('book_upto_amt',7,2)->nullable();
            $table->date('effect_date_from')->nullable();
            $table->date('effect_date_to')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('sysid')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('unique_no')->nullable();
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
        Schema::dropIfExists('gms_rate_code_history');
    }
}
