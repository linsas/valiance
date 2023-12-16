<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateTeamHistoryName extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::update('update player_team_history inner join team on player_team_history.fk_team = team.id set player_team_history.name = team.name where player_team_history.fk_team is not null');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
}
