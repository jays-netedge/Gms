<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sl_events', function (Blueprint $table) {
              $table->increments('id');
              $table->string('title')->nullable(); 
              $table->dateTime('fromDate')->nullable(); 
              $table->dateTime('toDate')->nullable();
              $table->date('addDate')->nullable(); 
              $table->string('keywords')->nullable(); 
              $table->string('contact')->nullable(); 
              $table->string('email')->nullable(); 
              $table->enum('en_comments',['0','1'])->nullable();
              $table->enum('comments',['0','1'])->nullable();
              $table->enum('is_archived',['0','1'])->nullable();
              $table->string('rss_date')->nullable();
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
        Schema::dropIfExists('sl_events');
    }
}
