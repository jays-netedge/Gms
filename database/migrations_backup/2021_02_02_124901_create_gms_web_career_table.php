<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsWebCareerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_web_career', function (Blueprint $table) {
               $table->increments('id');
               $table->string('career_job_interested')->nullable();
               $table->string('career_first_name')->nullable();
               $table->string('career_last_name')->nullable();
               $table->string('career_contact_no')->nullable();
               $table->string('career_exp_years')->nullable();
               $table->string('career_exp_months')->nullable();
               $table->string('career_email_id')->nullable();
               $table->string('career_resume')->nullable();
               $table->dateTime('posted_date')->nullable();
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
        Schema::dropIfExists('gms_web_career');
    }
}
