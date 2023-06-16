<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exit_money', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number') ;
            $table->dateTime('date');
            $table->integer('supplier_id') ;
            $table->decimal('amount') ;
            $table->integer('payment_method') ;
            $table->integer('user_created') ;
            $table->integer('based_on') ;
            $table->integer('type') ;
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
        Schema::dropIfExists('exit_money');
    }
}
