<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlMailinglistDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sl_mailinglist_drafts', function (Blueprint $table) {
              $table->increments('id');
              $table->string('subject')->nullable(); 
              $table->string('message')->default(1);
              $table->string('texthtml')->nullable();
              $table->string('lastsaved')->nullable();
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
        Schema::dropIfExists('sl_mailinglist_drafts');
    }
}
