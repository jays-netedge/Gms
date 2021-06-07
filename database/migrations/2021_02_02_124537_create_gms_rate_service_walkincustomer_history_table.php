<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateServiceWalkincustomerHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_service_walkincustomer_history', function (Blueprint $table) {
               $table->increments('id');
               $table->string('service_type')->nullable();
               $table->string('oda_type')->nullable();
               $table->decimal('min_weight',7,3)->nullable();
               $table->decimal('max_weight',7,3)->nullable();
               $table->integer('percentage')->nullable();
               $table->integer('amount')->nullable();
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
        Schema::dropIfExists('gms_rate_service_walkincustomer_history');
    }
}
