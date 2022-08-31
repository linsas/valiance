<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerTeamHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_team_history', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_since');
            $table->integer('fk_player')->unsigned();
            $table->integer('fk_team')->unsigned()->nullable();

            $table->unique(['fk_player', 'date_since']);
            $table->foreign('fk_player')->references('id')->on('player');
            $table->foreign('fk_team')->references('id')->on('team');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_team_history');
    }
}
