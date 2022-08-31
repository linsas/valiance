<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $seed
 * @property int|null $fk_team
 * @property int $fk_tournament
 * @property \App\Models\Team|null $team
 * @property \App\Models\Tournament $tournament
 * @property \Illuminate\Database\Eloquent\Collection $tournamentTeamPlayers
 */
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
