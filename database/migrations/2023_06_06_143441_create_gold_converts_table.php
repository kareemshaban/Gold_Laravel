<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoldConvertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gold_converts', function (Blueprint $table) {
            $table->id();
            $table -> date('date');
            $table -> string('doc_number');
            $table -> decimal('total21weight');
            $table -> text('notes');
            $table -> integer('user_created');
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
        Schema::dropIfExists('gold_converts');
    }
}
