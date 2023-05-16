<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $date_since
 * @property int $fk_player
 * @property ?int $fk_team
 * @property Player $player
 * @property ?Team $team
 */
class PlayerTeamHistory extends Model
{
    public $timestamps = false;
    protected $table = 'player_team_history';

    protected $visible = ['date_since', 'fk_player', 'fk_team'];

    /** @return BelongsTo<Player, PlayerTeamHistory> */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'fk_player');
    }

    /** @return BelongsTo<Team, PlayerTeamHistory> */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'fk_team');
    }
}
