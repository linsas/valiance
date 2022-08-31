<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigratePlayerTeamHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $players = DB::select('select * from player');
        $today = date('Y-m-d');
        foreach ($players as $player){
            if ($player->fk_team != null){
                DB::insert('insert into player_team_history (id, date_since, fk_player, fk_team) values (null, ?, ?, ?)', [ $today, $player->id, $player->fk_team ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $players = DB::select('select * from player');
        foreach ($players as $player){
            $history = DB::select('select * from player_team_history where fk_player = ? order by date_since desc', [ $player->id ]);
            $latestEntry = $history[0] ?? null;
            if ($latestEntry !== null){
                DB::update('update player set fk_team = ? where id = ?', [ $latestEntry->fk_team, $player->id ]);
            }
        }
    }
}
