<?php

namespace App\Models;

use App\Values\GameOutcome;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $number
 * @property int $fk_matchup
 * @property Matchup $matchup
 * @property ?int $fk_map
 * @property ?Map $map
 * @property ?int $score1
 * @property ?int $score2
 */
class Game extends Model
{
    public $timestamps = false;
    protected $table = 'game';

    protected $visible = ['map', 'score1', 'score2', 'number'];

    public static int $roundsPerHalf = 12;

    /** @return BelongsTo<Matchup, Game> */
    public function matchup(): BelongsTo
    {
        return $this->belongsTo(Matchup::class, 'fk_matchup');
    }

    public function map()
    {
        return $this->belongsTo('App\Models\Map', 'fk_map');
    }

    public function isCompleted(): bool
    {
        return $this->score1 !== null || $this->score2 !== null;
    }

    public function getOutcome(): GameOutcome
    {
        if (!$this->isCompleted()) return GameOutcome::Indeterminate;
        if ($this->score1 > $this->score2) return GameOutcome::Team1_Victory;
        if ($this->score2 > $this->score1) return GameOutcome::Team2_Victory;
        return GameOutcome::Indeterminate;
    }
}
