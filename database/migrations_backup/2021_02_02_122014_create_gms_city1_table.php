<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCity1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_city1', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city_code')->nullable();
            $table->string('city_name')->nullable();
            $table->integer('state_id')->default(0);
            $table->string('state_code')->nullable();
            $table->string('metro')->default('N');
            $table->string('city_rep_bo')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->unsignedInteger('user_id')->default(0);
            $table->integer('sys_id')->default(0);
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
        Schema::dropIfExists('gms_city1');
    }
}
