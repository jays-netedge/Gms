<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsDeptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_dept', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dept_code')->nullable();
            $table->string('dept_name')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('sysid')->nullable();
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
        Schema::dropIfExists('gms_dept');
    }
}
