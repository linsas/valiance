<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public $timestamps = false;
    protected $table = 'player';

    protected $visible = ['id', 'alias', 'fk_team'];

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'fk_team');
    }

    public function tournamentTeamPlayers()
    {
        return $this->hasMany('App\Models\TournamentTeamPlayer', 'fk_player');
    }
}
