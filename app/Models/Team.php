<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property Collection<int, PlayerTeamHistory> $history
 * @property Collection<int, TournamentTeam> $tournamentTeams
 */
class Team extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'team';

    protected $visible = ['id', 'name'];

    /** @return HasMany<PlayerTeamHistory> */
    public function history(): HasMany
    {
        return $this->hasMany(PlayerTeamHistory::class, 'fk_team');
    }

    /** @return HasMany<TournamentTeam> */
    public function tournamentTeams(): HasMany
    {
        return $this->hasMany(TournamentTeam::class, 'fk_team');
    }

    /** @return Collection<int, PlayerTeamHistory> */
    public function getJoinHistory(): Collection
    {
        return PlayerTeamHistory::with('player')->where('fk_team', $this->id)->get();
    }
}
