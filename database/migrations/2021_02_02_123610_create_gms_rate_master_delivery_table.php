<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsRateMasterDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_rate_master_delivery', function (Blueprint $table) {
                 $table->increments('id');
                 $table->integer('max_no')->nullable();
                 $table->string('del_rate_code')->nullable(); 
                 $table->string('flat_rate')->nullable();
                 $table->string('slab_rate')->nullable(); 
                 $table->string('from_wt')->nullable();
                 $table->string('to_wt')->nullable(); 
                 $table->string('rate')->nullable(); 
                 $table->string('addnl')->nullable(); 
                 $table->integer('addnl_type')->nullable(); 
                 $table->string('addnl_wt')->nullable(); 
                 $table->string('addnl_min')->nullable();
                 $table->string('addnl_max')->nullable();
                 $table->string('addnl_fixed')->nullable(); 
                 $table->string('addnl_rate')->nullable();
                 $table->string('non_from_wt')->nullable(); 
                 $table->string('non_to_wt')->nullable(); 
                 $table->string('non_rate')->nullable(); 
                 $table->string('non_addnl')->nullable(); 
                 $table->string('non_addnl_wt')->nullable();
                 $table->string('non_addnl_rate')->nullable();
                 $table->string('gd_from_wt')->nullable();
                 $table->string('gd_to_wt')->nullable(); 
                $table->string('gd_rate')->nullable(); 
                $table->string('gd_addnl')->nullable();
                $table->string('gd_addnl_wt')->nullable();
                $table->string('gd_addnl_rate')->nullable(); 
                $table->string('gd_non_from_wt')->nullable(); 
                $table->string('gd_non_to_wt')->nullable(); 
                $table->string('gd_non_rate')->nullable(); 
                $table->string('gd_non_addnl')->nullable();
                $table->string('gd_non_addnl_wt')->nullable();
                $table->string('gd_non_addnl_rate')->nullable(); 
                $table->string('lg_from_wt')->nullable(); 
                $table->string('lg_to_wt')->nullable(); 
                $table->string('lg_rate')->nullable(); 
                $table->string('lg_addnl')->nullable();
                $table->string('lg_addnl_wt')->nullable();
                $table->string('lg_addnl_rate')->nullable();
                $table->string('lg_non_from_wt')->nullable(); 
                $table->string('lg_non_to_wt')->nullable(); 
                $table->string('lg_non_rate')->nullable(); 
                $table->string('lg_non_addnl')->nullable();
                $table->string('lg_non_addnl_wt')->nullable();
                $table->string('lg_non_addnl_rate')->nullable(); 
                $table->decimal('max_limit_wt',7,3)->nullable(); 
                $table->decimal('max_limit_price',7,3)->nullable(); 
                $table->decimal('non_max_limit_wt',7,3)->nullable(); 
                $table->decimal('non_max_limit_price',7,3)->nullable(); 
                $table->decimal('gd_max_limit_wt',7,3)->nullable(); 
                $table->decimal('gd_max_limit_price',7,3)->nullable(); 
                $table->decimal('gd_non_max_limit_wt',7,3)->nullable(); 
                $table->decimal('gd_non_max_limit_price',7,3)->nullable(); 
                $table->decimal('lg_max_limit_wt',7,3)->nullable(); 
                $table->decimal('lg_max_limit_price',7,3)->nullable(); 
                $table->decimal('lg_non_max_limit_wt',7,3)->nullable(); 
                $table->decimal('lg_non_max_limit_price',7,3)->nullable(); 
                $table->decimal('tpy',7,3)->nullable(); 
                $table->decimal('cod',7,3)->nullable(); 
                $table->decimal('mps',7,3)->nullable(); 
                $table->decimal('fvo',7,3)->nullable(); 
                $table->decimal('fov',7,3)->nullable(); 
                $table->decimal('edl',7,3)->nullable();
                $table->decimal('isc',7,3)->nullable(); 
                $table->decimal('oda',7,3)->nullable();
                $table->string('status')->nullable(); 
                $table->dateTime('entry_date')->nullable(); 
                $table->string('created_by')->nullable();
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
        Schema::dropIfExists('gms_rate_master_delivery');
    }
}
