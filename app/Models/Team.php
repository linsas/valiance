<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public $timestamps = false;
    protected $table = 'team';

    protected $visible = ['id', 'name'];

    public function players()
    {
        return $this->hasMany('App\Models\Player', 'fk_team');
    }

    public function tournamentTeams()
    {
        return $this->hasMany('App\Models\TournamentTeam', 'fk_team');
    }
}
