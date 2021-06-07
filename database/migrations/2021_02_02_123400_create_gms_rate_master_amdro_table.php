<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateMasterAmdroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_master_amdro', function (Blueprint $table) {
           $table->increments('id');
           $table->unsignedInteger('unique_rate_id')->nullable();
           $table->string('product_code')->nullable(); 
           $table->unsignedInteger('scheme_rate_id')->nullable(); 
           $table->string('ro_code')->nullable(); 
           $table->string('bo_code')->nullable(); 
           $table->string('cust_type')->nullable();
           $table->string('org')->nullable();
           $table->string('dest')->nullable();
           $table->string('mode')->nullable();
           $table->string('doc_type')->nullable();
           $table->string('loc_type')->nullable(); 
           $table->string('flat_rate')->nullable(); 
           $table->string('slab_rate')->nullable(); 
           $table->string('min_charge_wt')->nullable(); 
           $table->string('from_wt')->nullable();
           $table->string('to_wt')->nullable(); 
           $table->string('rate')->nullable();
           $table->string('tranship_rate')->nullable();
           $table->string('addnl')->nullable(); 
           $table->integer('addnl_type')->nullable(); 
           $table->string('addnl_wt')->nullable(); 
           $table->string('addnl_min')->nullable();
           $table->string('addnl_max')->nullable();
           $table->string('addnl_fixed')->nullable(); 
           $table->string('addnl_rate')->nullable();
           $table->string('extra_rate')->nullable(); 
           $table->integer('tat')->nullable(); 
           $table->string('status')->nullable(); 
           $table->string('approved_status')->nullable(); 
           $table->dateTime('entry_date')->nullable();
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
        Schema::dropIfExists('gms_rate_master_amdro');
    }
}
