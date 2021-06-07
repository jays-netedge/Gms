<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlDistributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sl_distributors', function (Blueprint $table) {
              $table->increments('id');
              $table->string('company_name')->nullable(); 
              $table->string('name')->nullable(); 
              $table->string('city')->nullable();
              $table->string('mobile')->nullable(); 
              $table->string('email')->nullable(); 
              $table->string('website')->nullable(); 
              $table->integer('type')->nullable(); 
              $table->dateTime('posted_date')->nullable();
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
        Schema::dropIfExists('sl_distributors');
    }
}
