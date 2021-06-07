<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookBoIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_bo_issue', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('iss_ro_id')->nullable();
            $table->string('office_type')->nullable();
            $table->integer('qauantity')->nullable();
            $table->integer('cnno_start')->nullable();
            $table->integer('cnno_end')->nullable();
            $table->integer('total_allotted')->nullable();
            $table->integer('office_code')->nullable();
            $table->integer('rate_per_cnno')->nullable();
            $table->integer('status')->nullable();
            $table->string('transfer_status')->nullable();
            $table->string('entry_date')->nullable();
            $table->string('recieved_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('sysid')->nullable();
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
        Schema::dropIfExists('gms_book_bo_issue');
    }
}
