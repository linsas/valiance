<?php

namespace App\Models;

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

    /**
     * Returns a value corresponding to the outcome of this matchup.
     * @return int 1 for decisive team1 victory, -1 for a decisive team2 victory, 0 for an indeterminate outcome
     */
    public function getOutcome()
    {
        if (!$this->isCompleted()) return 0;
        if ($this->score1 > $this->score2) return 1;
        if ($this->score2 > $this->score1) return -1;
        return 0;
    }
}
