<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsEmpTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_emp_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('emp_type_code')->nullable();
            $table->string('emp_type_name')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('sysid')->nullable();
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
        Schema::dropIfExists('gms_emp_type');
    }
}
