<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsOfficeTimeBlockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_office_time_block', function (Blueprint $table) {
              $table->increments('id');
              $table->string('office_type')->nullable(); 
              $table->string('office_code')->nullable();
              $table->string('block_type')->nullable();
              $table->time('from_time')->nullable();
              $table->time('to_time')->nullable();
              $table->string('status')->nullable();
              $table->string('created_by')->nullable();
              $table->string('created_date')->nullable();
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
        Schema::dropIfExists('gms_office_time_block');
    }
}
