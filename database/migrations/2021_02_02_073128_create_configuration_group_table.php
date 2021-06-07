<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuration_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('configuration_group_title')->nullable();
            $table->string('configuration_group_description')->nullable();
            $table->integer('sort_order')->nullable();
            $table->integer('visible')->default(1);
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
        Schema::dropIfExists('configuration_group');
    }
}
