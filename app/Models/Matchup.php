<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $fk_team1
 * @property int $fk_team2
 * @property int $fk_round
 * @property \App\Models\Team $team1
 * @property \App\Models\Team $team2
 * @property \App\Models\Round $round
 * @property string $key
 * @property \Illuminate\Database\Eloquent\Collection $games
 * @method int getScore1()
 * @method int getScore2()
 */
class Matchup extends Model
{
    public $timestamps = false;
    protected $table = 'matchup';
    protected $with = ['team1', 'team2', 'games'];

    protected $fillable = ['fk_team1', 'fk_team2', 'fk_round', 'key'];
    protected $visible = ['id', 'team1', 'team2', 'games', 'key'];

    public function round()
    {
        return $this->belongsTo('App\Models\Round', 'fk_round');
    }

    public function team1()
    {
        return $this->belongsTo('App\Models\TournamentTeam', 'fk_team1');
    }

    public function team2()
    {
        return $this->belongsTo('App\Models\TournamentTeam', 'fk_team2');
    }

    public function games()
    {
        return $this->hasMany('App\Models\Game', 'fk_matchup');
    }

    public function getScore1()
    {
        $score = 0;
        foreach ($this->games as $game) {
            if ($game->getOutcome() === 1) $score++;
        }
        return $score;
    }

    public function getScore2()
    {
        $score = 0;
        foreach ($this->games as $game) {
            if ($game->getOutcome() === -1) $score++;
        }
        return $score;
    }

    /**
     * Returns a value corresponding to the outcome of this matchup.
     * @return int 1 for decisive team1 victory, -1 for a decisive team2 victory, 0 for an indeterminate outcome
     */
    public function getOutcome()
    {
        $score1 = $this->getScore1();
        $score2 = $this->getScore2();
        $total = $this->games->count();
        if ($score1 > $total / 2) return 1;
        if ($score2 > $total / 2) return -1;
        return 0;
    }
}
