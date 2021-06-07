<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_users', function (Blueprint $table) {
               $table->increments('id');
               $table->string('user_name')->nullable();
               $table->string('password')->nullable();
               $table->string('user_type')->nullable();
               $table->string('masters')->nullable();
               $table->string('transactions')->nullable();
               $table->string('reports')->nullable();
               $table->string('billing')->nullable();
               $table->string('status')->nullable();
               $table->dateTime('entry_date')->nullable();
               $table->string('userid')->nullable();
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
        Schema::dropIfExists('gms_users');
    }
}
