<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Collection $history
 * @property \Illuminate\Database\Eloquent\Collection $tournamentTeams
 */
class Team extends Model
{
    public $timestamps = false;
    protected $table = 'team';

    protected $visible = ['id', 'name'];

    public function history()
    {
        return $this->hasMany('App\Models\PlayerTeamHistory', 'fk_team');
    }

    public function tournamentTeams()
    {
        return $this->hasMany('App\Models\TournamentTeam', 'fk_team');
    }
}
