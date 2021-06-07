<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_state', function (Blueprint $table) {
               $table->increments('id');
               $table->integer('country_id')->nullable();
               $table->integer('zone_id')->nullable();
               $table->string('state_code')->nullable();
               $table->string('state_name')->nullable();
               $table->string('status')->nullable();
               $table->dateTime('entry_date')->nullable();
               $table->string('user_id')->nullable();
               $table->string('sys_id')->nullable();
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
        Schema::dropIfExists('gms_state');
    }
}
