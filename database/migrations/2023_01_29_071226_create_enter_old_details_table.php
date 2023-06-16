<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterOldDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enter_old_details', function (Blueprint $table) {
            $table->id();
            $table->integer('bill_id') ;
            $table->integer('item_id');
            $table->integer('karat_id');
            $table->decimal('weight');
            $table->decimal('weight21');
            $table->decimal('made_money_gram');
            $table->decimal('gold_price_gram');
            $table->decimal('tax_money_gram');
            $table->decimal('net_weight');
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
        Schema::dropIfExists('enter_old_details');
    }
}
