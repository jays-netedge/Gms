<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookReleaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_release', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description',200)->nullable();
            $table->string('multiple_cnno',200)->nullable();
            $table->string('cnno_start')->nullable();
            $table->string('cnno_end')->nullable();
            $table->string('block_type')->default('S');
            $table->string('status')->default('Y');
            $table->dateTime('entry_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('gms_book_release');
    }
}
