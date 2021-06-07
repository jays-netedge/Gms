<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookRoIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_ro_issue', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iss_zone')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->string('description',200)->nullable();
            $table->integer('qauantity')->nullable();
            $table->string('cnno_start')->nullable();
            $table->string('cnno_end')->nullable();
            $table->integer('total_allotted')->default(0);
            $table->string('office_code')->nullable();
            $table->string('status')->default(0);
            $table->string('transfer_status')->default('N');
            $table->dateTime('entry_date')->nullable();
            $table->dateTime('recieved_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('sysid')->nullable();
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
        Schema::dropIfExists('gms_book_ro_issue');
    }
}
