<?php

namespace App\Models;

use App\Values\GameOutcome;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $number
 * @property int $fk_matchup
 * @property \App\Models\Matchup $matchup
 * @property string|null $map
 * @property int|null $score1
 * @property int|null $score2
 */
class Game extends Model
{
    public $timestamps = false;
    protected $table = 'game';

    protected $fillable = ['fk_matchup', 'number', 'map', 'score1', 'score2'];
    protected $visible = ['map', 'score1', 'score2', 'number'];

    public static $validMaps = ['bind', 'haven', 'split', 'ascent', 'icebox', 'breeze', 'fracture', 'pearl',];
    public static $roundsPerHalf = 12;

    public function matchup()
    {
        return $this->belongsTo('App\Models\Matchup', 'fk_matchup');
    }

    /**
     * @return bool `true` if score information is entered, `false` otherwise
     */
    public function isCompleted()
    {
        return $this->score1 !== null && $this->score2 !== null;
    }

    public function getOutcome()
    {
        if (!$this->isCompleted()) return GameOutcome::Indeterminate;
        if ($this->score1 > $this->score2) return GameOutcome::Team1_Victory;
        if ($this->score2 > $this->score1) return GameOutcome::Team2_Victory;
        return GameOutcome::Indeterminate;
    }
}
