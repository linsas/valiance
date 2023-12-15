<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $seed
 * @property int $fk_team
 * @property int $fk_tournament
 * @property Team $team
 * @property Tournament $tournament
 * @property Collection<int, TournamentTeamPlayer> $tournamentTeamPlayers
 */
class TournamentTeam extends Model
{
    public $timestamps = false;
    protected $table = 'tournament_team';

    protected $fillable = ['name', 'seed', 'fk_team', 'fk_tournament'];

    /** @return BelongsTo<Tournament, TournamentTeam> */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class, 'fk_tournament');
    }

    /** @return BelongsTo<Team, TournamentTeam> */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'fk_team');
    }

    /** @return HasMany<TournamentTeamPlayer> */
    public function tournamentTeamPlayers(): HasMany
    {
        return $this->hasMany(TournamentTeamPlayer::class, 'fk_tournament_team');
    }
}
