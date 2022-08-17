<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentTeamPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_team_player', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_player')->unsigned();
            $table->integer('fk_tournament_team')->unsigned();

            $table->unique(['fk_player', 'fk_tournament_team']);
            $table->foreign('fk_player')->references('id')->on('player');
            $table->foreign('fk_tournament_team')->references('id')->on('tournament_team');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournament_team_player');
    }
}
