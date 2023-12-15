<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $fk_player
 * @property int $fk_tournament_team
 * @property Player $player
 * @property TournamentTeam $tournamentTeam
 */
class TournamentTeamPlayer extends Model
{
    public $timestamps = false;
    protected $table = 'tournament_team_player';

    protected $fillable = ['fk_player', 'fk_tournament_team'];

    /** @return BelongsTo<Player, TournamentTeamPlayer> */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'fk_player');
    }

    /** @return BelongsTo<TournamentTeam, TournamentTeamPlayer> */
    public function tournamentTeam(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'fk_tournament_team');
    }
}
