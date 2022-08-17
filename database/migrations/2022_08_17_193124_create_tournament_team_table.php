<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_team', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('seed')->unsigned();
            $table->integer('fk_tournament')->unsigned();
            $table->integer('fk_team')->unsigned();

            $table->unique(['fk_tournament', 'seed']);
            $table->unique(['fk_tournament', 'fk_team']);
            $table->foreign('fk_tournament')->references('id')->on('tournament');
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
        Schema::dropIfExists('tournament_team');
    }
}
