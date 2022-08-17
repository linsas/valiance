<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentTeamPlayer extends Model
{
    public $timestamps = false;
    protected $table = 'tournament_team_player';

    protected $visible = ['id', 'fk_player', 'fk_tournament_team'];

    public function player()
    {
        return $this->belongsTo('App\Models\Player', 'fk_player');
    }

    public function tournamentTeam()
    {
        return $this->belongsTo('App\Models\TournamentTeam', 'fk_tournament_team');
    }
}
