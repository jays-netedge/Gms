<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsTaxTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_tax_type', function (Blueprint $table) {
               $table->increments('id');
               $table->integer('tax_id')->nullable();
               $table->string('rate')->nullable();
               $table->dateTime('from_date')->nullable();
               $table->dateTime('to_date')->nullable();
               $table->string('status')->nullable();
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
        Schema::dropIfExists('gms_tax_type');
    }
}
