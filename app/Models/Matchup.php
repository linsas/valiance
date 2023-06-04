<?php

namespace App\Models;

use App\Values\GameOutcome;
use App\Values\MatchupOutcome;
use App\Values\MatchupSignificance;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $fk_team1
 * @property int $fk_team2
 * @property int $fk_round
 * @property TournamentTeam $team1
 * @property TournamentTeam $team2
 * @property Round $round
 * @property MatchupSignificance $significance
 * @property Collection<int, Game> $games
 */
class Matchup extends Model
{
    public $timestamps = false;
    protected $table = 'matchup';

    protected $visible = ['id', 'team1', 'team2', 'games', 'significance'];

    /** @return BelongsTo<Round, Matchup> */
    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class, 'fk_round');
    }

    /** @return BelongsTo<TournamentTeam, Matchup> */
    public function team1(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'fk_team1');
    }

    /** @return BelongsTo<TournamentTeam, Matchup> */
    public function team2(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'fk_team2');
    }

    /** @return HasMany<Game> */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'fk_matchup');
    }

    /** @return Attribute<MatchupSignificance, never> */
    protected function significance(): Attribute
    {
        return Attribute::make(
            get: fn (string $scalar) => MatchupSignificance::from($scalar),
        );
    }

    public function getTeam1Score(): int
    {
        $team1Score = 0;
        foreach ($this->games as $game) {
            if ($game->getOutcome() === GameOutcome::Team1_Victory) $team1Score++;
        }
        return $team1Score;
    }

    public function getTeam2Score(): int
    {
        $team2Score = 0;
        foreach ($this->games as $game) {
            if ($game->getOutcome() === GameOutcome::Team2_Victory) $team2Score++;
        }
        return $team2Score;
    }

    public function getOutcome(): MatchupOutcome
    {
        $team1Score = $this->getTeam1Score();
        $team2Score = $this->getTeam2Score();
        $total = $this->games->count();
        if ($team1Score > $total / 2) return MatchupOutcome::Team1_Victory;
        if ($team2Score > $total / 2) return MatchupOutcome::Team2_Victory;
        return MatchupOutcome::Indeterminate;
    }
}
