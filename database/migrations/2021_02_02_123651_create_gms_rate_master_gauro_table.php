<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateMasterGauroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_master_gauro', function (Blueprint $table) {
               $table->increments('id');
               $table->integer('unique_rate_id')->nullable();
               $table->string('product_code')->nullable(); 
               $table->integer('scheme_rate_id')->nullable();
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
               $table->decimal('min_charge_wt',7,3)->nullable(); 
               $table->decimal('from_wt',7,3)->nullable();
               $table->decimal('to_wt',7,3)->nullable(); 
               $table->decimal('rate',7,3)->nullable();
               $table->decimal('tranship_rate',7,3)->nullable();
               $table->string('addnl')->nullable(); 
               $table->integer('addnl_type')->nullable(); 
               $table->string('addnl_wt')->nullable(); 
               $table->string('addnl_min')->nullable();
               $table->string('addnl_max')->nullable();
               $table->string('addnl_fixed')->nullable(); 
               $table->string('addnl_rate')->nullable();
               $table->decimal('extra_rate',7,3)->nullable(); 
               $table->integer('tat')->nullable(); 
               $table->string('status')->nullable(); 
               $table->integer('approved_status')->nullable(); 
               $table->dateTime('entry_date')->nullable();
               $table->string('user_id')->nullable();
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
        Schema::dropIfExists('gms_rate_master_gauro');
    }
}
