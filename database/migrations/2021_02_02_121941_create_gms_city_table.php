<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_city', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city_code')->nullable();
            $table->string('city_name')->nullable();
            $table->integer('state_id')->nullable();
            $table->string('state_code')->nullable();
            $table->string('metro')->nullable();
            $table->string('city_rep_bo')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->unsignedInteger('user_id')->default(0);
            $table->integer('sys_id')->default(0);
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
        Schema::dropIfExists('gms_city');
    }
}
