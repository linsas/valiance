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
 * @property ?string $map
 * @property ?int $score1
 * @property ?int $score2
 */
class Game extends Model
{
    public $timestamps = false;
    protected $table = 'game';

    protected $visible = ['map', 'score1', 'score2', 'number'];

    public static array $validMaps = ['bind', 'haven', 'split', 'ascent', 'icebox', 'breeze', 'fracture', 'pearl',];
    public static int $roundsPerHalf = 12;

    public function matchup(): BelongsTo
    {
        return $this->belongsTo('App\Models\Matchup', 'fk_matchup');
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
