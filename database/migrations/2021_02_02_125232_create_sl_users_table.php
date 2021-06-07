<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sl_users', function (Blueprint $table) {
              $table->increments('id');
              $table->unsignedInteger('group_id')->nullable(); 
              $table->string('company')->default(1);
              $table->date('dob')->nullable();
              $table->string('city')->nullable();
              $table->string('person')->nullable();
              $table->string('phone')->nullable();
              $table->string('email')->nullable();
              $table->string('designation')->nullable();
              $table->string('department')->nullable();
              $table->string('status')->nullable();
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
        Schema::dropIfExists('sl_users');
    }
}
