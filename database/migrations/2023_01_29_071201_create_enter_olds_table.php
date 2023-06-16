<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterOldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enter_olds', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number') ;
            $table->dateTime('date');
            $table->integer('client_id');
            $table->decimal('total_money');
            $table->decimal('total21_gold');
            $table->decimal('paid_money');
            $table->decimal('remain_money');
            $table->decimal('paid_gold');
            $table->decimal('remain_gold');
            $table->text('notes');
            $table->integer('user_created');
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
        Schema::dropIfExists('enter_olds');
    }
}
