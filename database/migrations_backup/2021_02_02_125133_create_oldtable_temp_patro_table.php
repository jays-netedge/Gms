<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOldtableTempPatroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oldtable_temp_patro', function (Blueprint $table) {
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
                $table->string('extra_rate')->nullable();
                $table->integer('tat')->nullable(); 
                $table->string('status')->nullable(); 
                $table->integer('approved_status')->nullable(); 
                $table->string('entry_date')->nullable(); 
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
        Schema::dropIfExists('oldtable_temp_patro');
    }
}
