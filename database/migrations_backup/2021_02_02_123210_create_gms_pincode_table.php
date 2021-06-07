<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsPincodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_pincode', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pincode_value')->nullable(); 
            $table->string('service')->nullable();
            $table->string('city_code')->nullable();
            $table->string('rep_code')->nullable();
            $table->string('courier')->nullable();
            $table->string('gold')->nullable();
            $table->string('logistics')->nullable();
            $table->string('intracity')->nullable();
            $table->string('international')->nullable();
            $table->string('regular')->nullable();
            $table->string('topay')->nullable();
            $table->string('cod')->nullable();
            $table->string('topay_cod')->nullable();
            $table->string('oda')->nullable();
            $table->string('mentioned_piece')->nullable();
            $table->string('fov_or')->nullable();
            $table->string('fov_cr')->nullable();
            $table->string('isc')->nullable();
            $table->string('edl')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('pin_status')->nullable();
            $table->integer('user_id')->nullable();
            $table->date('posted')->nullable();
            $table->dateTime('entry_date_time')->nullable();
            $table->dateTime('update_date_time')->nullable();
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
        Schema::dropIfExists('gms_pincode');
    }
}
