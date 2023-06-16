<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code') ;
            $table->string('name_ar');
            $table->string('name_en');
            $table->integer('category_id');
            $table->integer('karat_id');
            $table->decimal('weight');
            $table->decimal('no_metal');
            $table->integer('no_metal_type');
            $table->decimal('made_Value');
            $table->integer('item_type');
            $table->decimal('tax');
            $table->integer('state');
            $table->string('img');
            $table->decimal('price');
            $table->decimal('cost');
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
        Schema::dropIfExists('items');
    }
}
