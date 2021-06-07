<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_complaint', function (Blueprint $table) {
            $table->increments('id');
            $table->string('log_cnno')->default(0);
            $table->string('consignee_mobile_no')->nullable();
            $table->string('consignee_name')->nullable();
            $table->string('consignor_mobile_no')->nullable();
            $table->string('consignor_name')->nullable();
            $table->string('status')->default(0);
            $table->dateTime('entry_date')->nullable();
            $table->string('bo_office')->nullable();
            $table->string('userid')->nullable();
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
        Schema::dropIfExists('gms_complaint');
    }
}
