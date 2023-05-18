<?php

namespace App\Services\Competition;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Services\Competition\Pool\CompetitorPool;
use App\Values\MatchupSignificance;

class ProgressionRule
{
    public MatchupSignificance $matchupSignificance;
    public int $numGames;
    private CompetitorPool $highComposite;
    private CompetitorPool $lowComposite;

    public function __construct(MatchupSignificance $matchupSignificance, int $numGames, CompetitorPool $highComposite, CompetitorPool $lowComposite)
    {
        $this->matchupSignificance = $matchupSignificance;
        $this->numGames = $numGames;
        $this->highComposite = $highComposite;
        $this->lowComposite = $lowComposite;
    }

    public function fill(Tournament $tournament): void
    {
        $this->highComposite->fill($tournament);
        $this->lowComposite->fill($tournament);
    }

    public function takeHigh(): TournamentTeam
    {
        return $this->highComposite->takeHigh();
    }

    public function takeLow(): TournamentTeam
    {
        return $this->lowComposite->takeLow();
    }
}
