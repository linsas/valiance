<?php

namespace App\Services\Competition\Format;

use App\Services\Competition\Pool\CompetitorPool;
use App\Services\Competition\ProgressionRule;
use App\Values\MatchupSignificance;

class SingleElimination4TeamFormat extends TournamentFormat
{
    public function getTeamsNeeded(): int
    {
        return 4;
    }

    public function getRules(): array
    {
        $startingComposite = CompetitorPool::fromSeed([1, 2, 3, 4]);
        return [
            [
                new ProgressionRule(MatchupSignificance::Semifinal_1, 1, $startingComposite, $startingComposite),
                new ProgressionRule(MatchupSignificance::Semifinal_2, 1, $startingComposite, $startingComposite),
            ],
            [
                new ProgressionRule(MatchupSignificance::Grand_Final, 3, CompetitorPool::fromMatchupWinner(MatchupSignificance::Semifinal_1), CompetitorPool::fromMatchupWinner(MatchupSignificance::Semifinal_2)),
            ],
        ];
    }
}
