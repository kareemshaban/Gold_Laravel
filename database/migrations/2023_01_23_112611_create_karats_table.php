<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karats', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar') ;
            $table->string('name_en');
            $table->string('label');
            $table->decimal('stamp_value') -> nullable() ->default(0);
            $table->decimal('transform_factor') ->default(1);

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
        Schema::dropIfExists('karats');
    }
}
