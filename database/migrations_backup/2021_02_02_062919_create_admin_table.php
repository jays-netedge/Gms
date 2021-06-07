<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('security_no')->nullable();
            $table->unsignedInteger('office_id')->nullable();
            $table->string('office_code')->nullable();
            $table->string('city')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('address')->nullable();
            $table->string('city_name')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->integer('pincode')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('serv_no')->nullable();
            $table->string('sac_hsn_code')->nullable();
            $table->string('logo')->nullable();
            $table->string('user_type')->nullable();
            $table->integer('status')->nullable();
            $table->integer('password_status')->nullable();
            $table->string('company_type')->nullable();
            $table->date('prev_pass_changed')->nullable();
            $table->date('last_pass_changed')->nullable();
            $table->date('last_log_ip')->nullable();
            $table->integer('session_time')->nullable();
            $table->integer('session_status')->nullable();
            $table->date('last_login_date')->nullable();
            $table->date('last_log_date')->nullable();
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
        Schema::dropIfExists('admin');
    }
}
