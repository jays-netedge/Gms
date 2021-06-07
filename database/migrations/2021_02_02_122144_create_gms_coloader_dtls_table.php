<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsColoaderDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_coloader_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('c_type')->default('OPMF');
            $table->string('branch_code')->nullable();
            $table->string('branch_emp_code')->nullable();
            $table->string('branch_ro')->nullable();
            $table->string('coloader_code')->nullable();
            $table->string('cd_no')->nullable();
            $table->string('coloader_srno')->nullable();
            $table->string('coloader_name')->nullable();
            $table->string('coloader_phone')->nullable();
            $table->string('coloader_mobile')->nullable();
            $table->string('coloader_bus_no')->nullable();
            $table->string('coloader_description')->nullable();
            $table->string('cd_bags')->nullable();
            $table->string('coloader_wt')->nullable();
            $table->string('coloader_mode')->nullable();
            $table->date('coloader_date')->nullable();
            $table->string('coloader_dest')->nullable();
            $table->string('coloader_dest_ro')->nullable();
            $table->string('coloader_dest_bo')->nullable();
            $table->string('coloader_dest_city')->nullable();
            $table->string('coloader_type')->nullable();
            $table->string('coloader_cust_type')->nullable();
            $table->string('coloader_cust_code')->nullable();
            $table->string('manifest_no')->nullable();
            $table->date('manifest_date')->nullable();
            $table->integer('total_cnno')->nullable();
            $table->decimal('total_wt',8,3)->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('gms_coloader_dtls');
    }
}
