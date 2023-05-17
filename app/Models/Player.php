<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $alias
 * @property Collection<int, PlayerTeamHistory> $history
 * @property Collection<int, TournamentTeamPlayer> $tournamentTeamPlayers
 */
class Player extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'player';

    protected $visible = ['id', 'alias'];

    /** @return HasMany<PlayerTeamHistory> */
    public function history(): HasMany
    {
        return $this->hasMany(PlayerTeamHistory::class, 'fk_player');
    }

    /** @return HasMany<TournamentTeamPlayer> */
    public function tournamentTeamPlayers(): HasMany
    {
        return $this->hasMany(TournamentTeamPlayer::class, 'fk_player');
    }

    public function getLatestHistory(): ?PlayerTeamHistory
    {
        return PlayerTeamHistory::where('fk_player', $this->id)->orderByDesc('date_since')->first();
    }
}
