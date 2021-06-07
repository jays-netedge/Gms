<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_controls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('office_type')->nullable();
            $table->string('office_code')->nullable();
            $table->string('cust_type')->nullable();
            $table->string('cust_code')->nullable();
            $table->string('check_code')->nullable();
            $table->dateTime('from_date')->nullable();
            $table->dateTime('to_date')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('gms_book_controls');
    }
}
