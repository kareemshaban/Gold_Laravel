<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitWorkDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exit_work_details', function (Blueprint $table) {
            $table->id();
            $table->integer('bill_id') ;
            $table->integer('item_id') ;
            $table->integer('karat_id');
            $table->decimal('weight');
            $table->decimal('gram_price');
            $table->decimal('gram_manufacture');
            $table->decimal('gram_tax');
            $table->decimal('net_money');
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
        Schema::dropIfExists('exit_work_details');
    }
}
