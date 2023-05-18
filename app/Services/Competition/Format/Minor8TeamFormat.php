<?php

namespace App\Services\Competition\Format;

use App\Services\Competition\Pool\CompetitorPool;
use App\Services\Competition\Pool\CompetitorSourceMatchup;
use App\Services\Competition\ProgressionRule;
use App\Values\MatchupSignificance;

class Minor8TeamFormat extends TournamentFormat
{
    public function getTeamsNeeded(): int
    {
        return 8;
    }

    public function getRules(): array
    {
        $groupA = CompetitorPool::fromSeed([1, 3, 5, 7]);
        $groupB = CompetitorPool::fromSeed([2, 4, 6, 8]);

        $round1AWinners = CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_A_Opening_Match);
        $round1ALosers = CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_A_Opening_Match);
        $round1BWinners = CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_B_Opening_Match);
        $round1BLosers = CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_B_Opening_Match);

        $semifinalists = new CompetitorPool([
            new CompetitorSourceMatchup(MatchupSignificance::Group_A_Upper_Bracket_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Group_B_Upper_Bracket_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Group_A_Deciding_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Group_B_Deciding_Match),
        ]);

        return [
            [
                new ProgressionRule(MatchupSignificance::Group_A_Opening_Match, 1, $groupA, $groupA),
                new ProgressionRule(MatchupSignificance::Group_A_Opening_Match, 1, $groupA, $groupA),
                new ProgressionRule(MatchupSignificance::Group_B_Opening_Match, 1, $groupB, $groupB),
                new ProgressionRule(MatchupSignificance::Group_B_Opening_Match, 1, $groupB, $groupB),
            ],
            [
                new ProgressionRule(MatchupSignificance::Group_A_Upper_Bracket_Match, 1, $round1AWinners, $round1AWinners),
                new ProgressionRule(MatchupSignificance::Group_A_Lower_Bracket_Match, 3, $round1ALosers, $round1ALosers),
                new ProgressionRule(MatchupSignificance::Group_B_Upper_Bracket_Match, 1, $round1BWinners, $round1BWinners),
                new ProgressionRule(MatchupSignificance::Group_B_Lower_Bracket_Match, 3, $round1BLosers, $round1BLosers),
            ],
            [
                new ProgressionRule(MatchupSignificance::Group_A_Deciding_Match, 3, CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_A_Upper_Bracket_Match), CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_A_Lower_Bracket_Match)),
                new ProgressionRule(MatchupSignificance::Group_B_Deciding_Match, 3, CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_B_Upper_Bracket_Match), CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_B_Lower_Bracket_Match)),
            ],
            [
                new ProgressionRule(MatchupSignificance::Semifinal_1, 3, $semifinalists, $semifinalists),
                new ProgressionRule(MatchupSignificance::Semifinal_2, 3, $semifinalists, $semifinalists),
            ],
            [
                new ProgressionRule(MatchupSignificance::Grand_Final, 5, CompetitorPool::fromMatchupWinner(MatchupSignificance::Semifinal_1), CompetitorPool::fromMatchupWinner(MatchupSignificance::Semifinal_2)),
            ],
        ];
    }
}
