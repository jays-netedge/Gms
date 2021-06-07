<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsWebNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_web_news', function (Blueprint $table) {
               $table->increments('id');
               $table->string('title')->nullable();
               $table->string('description')->nullable();
               $table->string('image')->nullable();
               $table->integer('type')->nullable();
               $table->integer('status')->nullable();
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
        Schema::dropIfExists('gms_web_news');
    }
}
