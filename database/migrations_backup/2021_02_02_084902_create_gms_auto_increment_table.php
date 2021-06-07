<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsAutoIncrementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_auto_increment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('office_code')->nullable();
            $table->string('table_name')->nullable();
            $table->integer('present_increment')->default(0);
            $table->integer('outgoing_packet_manifest')->default(1);
            $table->integer('outgoing_master_manifest')->default(1);
            $table->integer('delivery_manifest')->default(1);
            $table->integer('co_mail')->nullable();
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
        Schema::dropIfExists('gms_auto_increment');
    }
}
