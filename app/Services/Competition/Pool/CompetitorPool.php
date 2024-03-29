<?php

namespace App\Services\Competition\Pool;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Values\MatchupSignificance;
use Illuminate\Support\Collection;

class CompetitorPool
{
    /** @var array<CompetitorPoolSource> */
    private array $sources;

    /** @var Collection<int, TournamentTeam> */
    private Collection $teams;

    /** @param array<CompetitorPoolSource> $sources */
    public function __construct(array $sources = [])
    {
        $this->sources = $sources;
        $this->teams = collect();
    }

    public function fill(Tournament $tournament): void
    {
        if ($this->teams->count() !== 0) return;

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

    /** @param array<int> $seeds */
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
