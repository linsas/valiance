<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $alias
 * @property \Illuminate\Database\Eloquent\Collection $history
 */
class Player extends Model
{
    public $timestamps = false;
    protected $table = 'player';

    protected $visible = ['id', 'alias'];

    public function history()
    {
        return $this->hasMany('App\Models\PlayerTeamHistory', 'fk_player');
    }

    public function tournamentTeamPlayers()
    {
        return $this->hasMany('App\Models\TournamentTeamPlayer', 'fk_player');
    }
}
