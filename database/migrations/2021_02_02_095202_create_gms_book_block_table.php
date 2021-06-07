<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookBlockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_block', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('description')->nullable();
            $table->string('multiple_cnno')->nullable();
            $table->string('cnno_start')->nullable();
            $table->string('cnno_end')->nullable();
            $table->string('block_type')->nullable();
            $table->string('status')->nullable();
            $table->string('created_by')->nullble();
            $table->dateTime('entry_date')->nullable();
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
        Schema::dropIfExists('gms_book_block');
    }
}
