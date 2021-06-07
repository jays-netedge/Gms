<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsBookCustIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_book_cust_issue', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('iss_bo_id')->nullable();
            $table->string('cust_type')->nullable();
            $table->string('cust_code')->nullable();
            $table->string('description')->nullable();
            $table->integer('qauantity')->nullable();
            $table->integer('cnno_start')->nullable();
            $table->string('cnno_end')->nullable();
            $table->integer('total_allotted')->nullable();
            $table->string('office_code')->nullable();
            $table->string('office_ro')->default('BLRRO');
            $table->string('created_by')->default('BO');
            $table->decimal('rate_per_cnno',7,2)->default(0);
            $table->integer('status')->nullable();
            $table->string('transfer_status')->default('N');
            $table->string('completed_status')->default('N');
            $table->date('entry_date')->nullable();
            $table->date('recieved_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('sysid')->nullable();
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
        Schema::dropIfExists('gms_book_cust_issue');
    }
}
