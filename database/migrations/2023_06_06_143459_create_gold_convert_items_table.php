<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoldConvertItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gold_convert_items', function (Blueprint $table) {
            $table->id();
            $table -> integer('docId');
            $table -> integer('item_id');
            $table -> integer('karat_id');
            $table -> decimal('weight');
            $table -> decimal('weight21');
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
        Schema::dropIfExists('gold_convert_items');
    }
}
