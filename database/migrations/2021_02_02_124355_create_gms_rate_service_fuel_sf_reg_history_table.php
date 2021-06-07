<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateServiceFuelSfRegHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_service_fuel_sf_reg_history', function (Blueprint $table) {
               $table->increments('id');
               $table->integer('scheme_rate_id')->nullable();
               $table->string('original_rate_id')->nullable();
               $table->string('bo_code')->nullable();
               $table->integer('fuel_type')->nullable();
               $table->decimal('flat_fuel_percentage',7,2)->nullable();
               $table->string('slab_fuel_id')->nullable(); 
               $table->string('slab_fuel_from')->nullable(); 
               $table->string('slab_fuel_to')->nullable();
               $table->string('slab_fuel_percentage')->nullable();
               $table->string('docket_type')->nullable(); 
               $table->string('docket_dx')->nullable();
               $table->string('docket_nx')->nullable();
               $table->decimal('book_upto_weight',7,3)->nullable();
               $table->decimal('book_upto_amt',7,2)->nullable();
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
        Schema::dropIfExists('gms_rate_service_fuel_sf_reg_history');
    }
}
