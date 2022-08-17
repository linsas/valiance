<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matchup extends Model
{
    public $timestamps = false;
    protected $table = 'matchup';
    protected $with = ['team1', 'team2', 'games'];

    protected $fillable = ['fk_team1', 'fk_team2', 'fk_round', 'key'];
    protected $visible = ['id', 'team1', 'team2', 'games', 'key'];

    public function round()
    {
        return $this->belongsTo('App\Models\Round', 'fk_round');
    }

    public function team1()
    {
        return $this->belongsTo('App\Models\TournamentTeam', 'fk_team1');
    }

    public function team2()
    {
        return $this->belongsTo('App\Models\TournamentTeam', 'fk_team2');
    }

    public function games()
    {
        return $this->hasMany('App\Models\Game', 'fk_matchup');
    }
}
