<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enter_money', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number') ;
            $table->dateTime('date');
            $table->integer('client_id') ;
            $table->decimal('amount') ;
            $table->integer('payment_method') ;
            $table->integer('user_created') ;
            $table->integer('based_on') ;
            $table->text('notes') ;
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
        Schema::dropIfExists('enter_money');
    }
}
