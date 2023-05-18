<?php

namespace App\Services\Competition\Format;

use App\Services\Competition\Pool\CompetitorPool;
use App\Services\Competition\ProgressionRule;
use App\Values\MatchupSignificance;

class SingleElimination8TeamFormat extends TournamentFormat
{
    public function getTeamsNeeded(): int
    {
        return 8;
    }

    public function getRules(): array
    {
        $startingComposite = CompetitorPool::fromSeed([1, 2, 3, 4, 5, 6, 7, 8]);
        return [
            [
                new ProgressionRule(MatchupSignificance::Quarterfinal_1, 1, $startingComposite, $startingComposite),
                new ProgressionRule(MatchupSignificance::Quarterfinal_2, 1, $startingComposite, $startingComposite),
                new ProgressionRule(MatchupSignificance::Quarterfinal_3, 1, $startingComposite, $startingComposite),
                new ProgressionRule(MatchupSignificance::Quarterfinal_4, 1, $startingComposite, $startingComposite),
            ],
            [
                new ProgressionRule(MatchupSignificance::Semifinal_1, 1, CompetitorPool::fromMatchupWinner(MatchupSignificance::Quarterfinal_1), CompetitorPool::fromMatchupWinner(MatchupSignificance::Quarterfinal_2)),
                new ProgressionRule(MatchupSignificance::Semifinal_2, 1, CompetitorPool::fromMatchupWinner(MatchupSignificance::Quarterfinal_3), CompetitorPool::fromMatchupWinner(MatchupSignificance::Quarterfinal_4)),
            ],
            [
                new ProgressionRule(MatchupSignificance::Grand_Final, 3, CompetitorPool::fromMatchupWinner(MatchupSignificance::Semifinal_1), CompetitorPool::fromMatchupWinner(MatchupSignificance::Semifinal_2)),
            ],
        ];
    }
}
