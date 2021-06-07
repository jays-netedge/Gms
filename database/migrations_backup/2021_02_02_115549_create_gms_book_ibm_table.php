<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookIbmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_ibm', function (Blueprint $table) {
            $table->increments('id');
            $table->string('BOOK_CODE')->nullable();
            $table->integer('BOOK_NAME')->nullable();
            $table->date('BOOK_FROM_DATE')->nullable();
            $table->date('BOOK_TO_DATE')->nullable();
            $table->string('BOOK_CNNO')->nullable();
            $table->date('BOOK_MFDATE')->nullable();
            $table->string('BOOK_DEST')->nullable();
            $table->decimal('BOOK_WT',8,3)->nullable();
            $table->string('BOOK_MODE')->nullable();
            $table->string('BOOK_DOC')->nullable();
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
        Schema::dropIfExists('gms_book_ibm');
    }
}
