<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsColoaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_coloader', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coloader_type')->nullable();
            $table->string('coloader_code')->nullable();
            $table->string('coloader_name')->nullable();
            $table->string('coloader_add1')->nullable();
            $table->string('coloader_add2')->nullable();
            $table->string('coloader_contact')->nullable();
            $table->string('coloader_phone')->nullable();
            $table->string('coloader_rep_offtype')->nullable();
            $table->string('coloader_rep_office')->nullable();
            $table->string('coloader_ro')->nullable();
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
        Schema::dropIfExists('gms_coloader');
    }
}
