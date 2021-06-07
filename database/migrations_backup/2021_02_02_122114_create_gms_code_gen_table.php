<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmsCodeGenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gms_code_gen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('CODE_TYPE')->nullable();
            $table->decimal('STARTNO', 7,0)->nullable();
            $table->decimal('ENDNO', 7,0)->nullable();
            $table->decimal('LASTNO', 7,0)->nullable();
            $table->string('STATUS')->nullable();
            $table->dateTime('ENTRY_DATE')->nullable();
            $table->string('USERID')->nullable();
            $table->string('SYSID')->nullable();
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
        Schema::dropIfExists('gms_code_gen');
    }
}
