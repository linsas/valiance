<?php

namespace App\Services\Competition\Pool;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Values\MatchupOutcome;
use App\Values\MatchupSignificance;
use Illuminate\Support\Collection;

class CompetitorSourceMatchup extends CompetitorPoolSource
{
    private MatchupSignificance $matchupSignificance;
    private bool $isWinner;

    public function __construct(MatchupSignificance $matchupSignificance, bool $isWinner = true)
    {
        $this->matchupSignificance = $matchupSignificance;
        $this->isWinner = $isWinner;
    }

    /** @return Collection<int, TournamentTeam> */
    public function collectParticipants(Tournament $tournament): Collection
    {
        $matchups = $tournament->matchups->where('significance', $this->matchupSignificance);
        $collection = collect();
        foreach ($matchups as $matchup) {
            $outcome = $matchup->getOutcome();
            $participantTeam = null;
            if ($outcome === MatchupOutcome::Team1_Victory) $participantTeam = $this->isWinner ? $matchup->team1 : $matchup->team2;
            if ($outcome === MatchupOutcome::Team2_Victory) $participantTeam = $this->isWinner ? $matchup->team2 : $matchup->team1;
            if ($participantTeam != null) $collection->push($participantTeam);
        }
        return $collection;
    }
}
