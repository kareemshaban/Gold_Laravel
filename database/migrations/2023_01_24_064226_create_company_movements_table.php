<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_movements', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->double('paid_money');
            $table->double('credit_money');
            $table->double('debit_money');
            $table->double('paid_gold');
            $table->double('credit_gold');
            $table->double('debit_gold');
            $table->string('date');
            $table->string('invoice_type');
            $table->double('bill_id');
            $table->string('bill_number');
            $table->string('user_created');
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
        Schema::dropIfExists('company_movements');
    }
}
