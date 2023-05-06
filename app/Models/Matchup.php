<?php

namespace App\Models;

use App\Values\GameOutcome;
use App\Values\MatchupOutcome;
use App\Values\MatchupSignificance;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $fk_team1
 * @property int $fk_team2
 * @property int $fk_round
 * @property \App\Models\Team $team1
 * @property \App\Models\Team $team2
 * @property \App\Models\Round $round
 * @property MatchupSignificance $significance
 * @property \Illuminate\Database\Eloquent\Collection $games
 */
class Matchup extends Model
{
    public $timestamps = false;
    protected $table = 'matchup';
    protected $with = ['team1', 'team2', 'games'];

    protected $fillable = ['fk_team1', 'fk_team2', 'fk_round', 'significance'];
    protected $visible = ['id', 'team1', 'team2', 'games', 'significance'];

    public function round(): BelongsTo
    {
        return $this->belongsTo('App\Models\Round', 'fk_round');
    }

    public function team1(): BelongsTo
    {
        return $this->belongsTo('App\Models\TournamentTeam', 'fk_team1');
    }

    public function team2(): BelongsTo
    {
        return $this->belongsTo('App\Models\TournamentTeam', 'fk_team2');
    }

    public function games(): HasMany
    {
        return $this->hasMany('App\Models\Game', 'fk_matchup');
    }

    protected function significance(): Attribute
    {
        return Attribute::make(
            get: fn (string $scalar) => MatchupSignificance::from($scalar),
            set: fn (MatchupSignificance $object) => $object->value,
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
