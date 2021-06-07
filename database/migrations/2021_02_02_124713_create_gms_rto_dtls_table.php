<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRtoDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rto_dtls', function (Blueprint $table) {
               $table->increments('id');
               $table->string('rto_branch')->nullable();
               $table->string('rto_recv_type')->nullable();
               $table->string('rto_recv_code')->nullable();
               $table->string('rto_mfno')->nullable();
               $table->string('rto_mfdate')->nullable();
               $table->decimal('rto_mftime',4,0)->nullable();
               $table->decimal('rto_srno',3,0)->nullable();
               $table->string('rto_cnno')->nullable();
               $table->string('rto_reason')->nullable();
               $table->string('rto_remarks')->nullable();
               $table->dateTime('entry_date')->nullable();
               $table->string('status')->nullable();
               $table->string('userid')->nullable(); 
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
        Schema::dropIfExists('gms_rto_dtls');
    }
}
