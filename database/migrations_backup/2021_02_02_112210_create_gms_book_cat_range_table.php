<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookCatRangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_cat_range', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('book_cat_id')->nullable();
            $table->string('cnno_start')->nullable();
            $table->string('cnno_end')->nullable();
            $table->decimal('upto_weight',7,3)->nullable();
            $table->decimal('upto_amt',7,2)->nullable();
            $table->string('status')->nullable();
            $table->date('entry_date')->nullable();
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
        Schema::dropIfExists('gms_book_cat_range');
    }
}
