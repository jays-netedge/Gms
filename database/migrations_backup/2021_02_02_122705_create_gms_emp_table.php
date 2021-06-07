<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsEmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_emp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('emp_code')->nullable();
            $table->integer('emp_num')->nullable();
            $table->string('emp_name')->nullable();
            $table->dateTime('emp_city')->nullable();
            $table->string('emp_add1')->nullable();
            $table->string('emp_add2')->nullable();
            $table->string('emp_phone')->nullable();
            $table->string('emp_email')->nullable();
            $table->string('emp_sex')->nullable();
            $table->string('emp_bldgrp')->nullable();
            $table->date('emp_dob')->nullable();
            $table->string('emp_education')->nullable();
            $table->string('emp_qualification')->nullable();
            $table->date('emp_doj')->nullable();
            $table->string('emp_dept')->nullable();
            $table->string('emp_dsg')->nullable();
            $table->string('emp_work_type')->nullable();
            $table->string('emp_status')->nullable();
            $table->date('emp_dor')->nullable();
            $table->string('emp_type')->nullable();
            $table->string('emp_rep_offtype')->nullable();
            $table->string('emp_rep_office')->nullable();
            $table->string('emp_rep_office_ro')->nullable();
            $table->string('delivery_code')->nullable();
            $table->integer('delivery_branch_status')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('profile_image_small')->nullable();
            $table->date('entry_date')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('gms_emp');
    }
}
