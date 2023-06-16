<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id();
            $table -> string('name_ar') -> nullable();
            $table -> string('name_en')-> nullable();
            $table -> string('phone') -> nullable();
            $table -> string('phone2') -> nullable();
            $table -> string('fax') -> nullable();
            $table -> string('email') -> nullable();
            $table -> string('website') -> nullable();
            $table -> string('taxNumber') -> nullable();
            $table -> string('registrationNumber') -> nullable();
            $table -> text('address');
            $table -> string('currency_ar') -> nullable();
            $table -> string('currency_en') -> nullable();
            $table -> string('currency_label') -> nullable();
            $table -> string('logo') -> nullable();
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
        Schema::dropIfExists('company_infos');
    }
}
