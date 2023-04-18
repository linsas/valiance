<?php

namespace App\Models;

use App\Values\GameOutcome;
use App\Values\MatchupOutcome;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $fk_team1
 * @property int $fk_team2
 * @property int $fk_round
 * @property \App\Models\Team $team1
 * @property \App\Models\Team $team2
 * @property \App\Models\Round $round
 * @property string $significance
 * @property \Illuminate\Database\Eloquent\Collection $games
 * @method int getScore1()
 * @method int getScore2()
 */
class Matchup extends Model
{
    public $timestamps = false;
    protected $table = 'matchup';
    protected $with = ['team1', 'team2', 'games'];

    protected $fillable = ['fk_team1', 'fk_team2', 'fk_round', 'significance'];
    protected $visible = ['id', 'team1', 'team2', 'games', 'significance'];

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
        $team1Score = 0;
        foreach ($this->games as $game) {
            if ($game->getOutcome() === 1) $score++;
        }
        return $score;
    }

    public function getScore2()
    {
        $team2Score = 0;
        foreach ($this->games as $game) {
            if ($game->getOutcome() === GameOutcome::Team2_Victory) $team2Score++;
        }
        return $team2Score;
    }

    public function getOutcome()
    {
        $team1Score = $this->getScore1();
        $team2Score = $this->getScore2();
        $total = $this->games->count();
        if ($team1Score > $total / 2) return MatchupOutcome::Team1_Victory;
        if ($team2Score > $total / 2) return MatchupOutcome::Team2_Victory;
        return MatchupOutcome::Indeterminate;
    }
}
