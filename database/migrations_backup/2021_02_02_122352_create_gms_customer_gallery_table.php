<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCustomerGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_customer_gallery', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->nullable();
            $table->string('cust_code')->nullable();
            $table->string('tittle')->nullable();
            $table->string('description')->nullable();
            $table->string('customer_gal_img')->nullable();
            $table->integer('status')->nullable();
            $table->string('cust_ro')->nullable();
            $table->string('created_office')->nullable();
            $table->date('posted_date')->nullable();
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
        Schema::dropIfExists('gms_customer_gallery');
    }
}
