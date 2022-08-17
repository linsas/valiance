<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matchup', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_round')->unsigned();
            $table->string('key');
            $table->integer('fk_team1')->unsigned();
            $table->integer('fk_team2')->unsigned();

            $table->foreign('fk_round')->references('id')->on('round');
            $table->foreign('fk_team1')->references('id')->on('tournament_team');
            $table->foreign('fk_team2')->references('id')->on('tournament_team');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matchup');
    }
}
