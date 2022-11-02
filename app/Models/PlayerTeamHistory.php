<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $date_since
 * @property int $fk_player
 * @property int|null $fk_team
 * @property \App\Models\Player $player
 * @property \App\Models\Team|null $team
 */
class PlayerTeamHistory extends Model
{
    public $timestamps = false;
    protected $table = 'player_team_history';

    protected $visible = ['date_since', 'fk_player', 'fk_team'];

    public function player()
    {
        return $this->belongsTo('App\Models\Player', 'fk_player');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'fk_team');
    }

    public function getLaterByPlayer()
    {
        return static::where('fk_player', $this->fk_player)
            ->where('date_since', '>', $this->date_since)
            ->orderBy('date_since')
            ->first()
        ;
    }
}
