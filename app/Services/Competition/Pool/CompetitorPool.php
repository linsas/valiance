<?php

namespace App\Services\Competition\Pool;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Values\MatchupSignificance;
use Illuminate\Support\Collection;

class CompetitorPool
{
    private array $sources;
    private Collection $teams;

    public function __construct(array $sources = [])
    {
        $this->sources = $sources;
    }

    public function fill(Tournament $tournament): void
    {
        if ($this->teams != null) return;
        $this->teams = collect();
        foreach ($this->sources as $source) {
            $collected = $source->collectParticipants($tournament);
            foreach ($collected as $participant) {
                $this->teams->push($participant);
            }
        }
    }

    public function takeHigh(): TournamentTeam
    {
        return $this->teams->shift();
    }

    public function takeLow(): TournamentTeam
    {
        return $this->teams->pop();
    }

    public static function fromSeed(array $seeds): CompetitorPool
    {
        return new CompetitorPool([new CompetitorSourceSeed($seeds)]);
    }

    public static function fromMatchupWinner(MatchupSignificance $matchupSignificance): CompetitorPool
    {
        return new CompetitorPool([new CompetitorSourceMatchup($matchupSignificance, true)]);
    }

    public static function fromMatchupLoser(MatchupSignificance $matchupSignificance): CompetitorPool
    {
        return new CompetitorPool([new CompetitorSourceMatchup($matchupSignificance, false)]);
    }
}
