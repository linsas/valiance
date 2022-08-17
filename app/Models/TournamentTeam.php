<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentTeam extends Model
{
    public $timestamps = false;
    protected $table = 'tournament_team';

    protected $visible = ['id', 'name', 'seed'];

    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament', 'fk_tournament');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'fk_team');
    }

    public function tournamentTeamPlayers()
    {
        return $this->hasMany('App\Models\TournamentTeamPlayer', 'fk_tournament_team');
    }
}
