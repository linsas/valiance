<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_matchup')->unsigned();
            $table->integer('number');
            $table->string('map')->nullable();
            $table->integer('score1')->nullable();
            $table->integer('score2')->nullable();

            $table->unique(['fk_matchup', 'number']);
            $table->foreign('fk_matchup')->references('id')->on('matchup');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game');
    }
}
