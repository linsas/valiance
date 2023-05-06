<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function player(): BelongsTo
    {
        return $this->belongsTo('App\Models\Player', 'fk_player');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo('App\Models\Team', 'fk_team');
    }
}
