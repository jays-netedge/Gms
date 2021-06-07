<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateServiceSfDirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_service_sf_dir', function (Blueprint $table) {
               $table->increments('id');
               $table->unsignedInteger('scheme_rate_id')->nullable();
               $table->string('service_type')->nullable();
               $table->string('oda_type')->nullable();
               $table->decimal('min_weight',7,3)->nullable();
               $table->decimal('max_weight',7,2)->nullable();
               $table->string('percentage')->nullable(); 
               $table->string('amount')->nullable(); 
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
        Schema::dropIfExists('gms_rate_service_sf_dir');
    }
}
