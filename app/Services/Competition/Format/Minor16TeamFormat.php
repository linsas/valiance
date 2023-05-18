<?php

namespace App\Services\Competition\Format;

use App\Services\Competition\Pool\CompetitorPool;
use App\Services\Competition\Pool\CompetitorSourceMatchup;
use App\Services\Competition\ProgressionRule;
use App\Values\MatchupSignificance;

class Minor16TeamFormat extends TournamentFormat
{
    public function getTeamsNeeded(): int
    {
        return 16;
    }

    public function getRules(): array
    {
        $groupA = CompetitorPool::fromSeed([1, 5, 9,  13]);
        $groupB = CompetitorPool::fromSeed([2, 6, 10, 14]);
        $groupC = CompetitorPool::fromSeed([3, 7, 11, 15]);
        $groupD = CompetitorPool::fromSeed([4, 8, 12, 16]);

        $round1AWinners = CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_A_Opening_Match);
        $round1ALosers = CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_A_Opening_Match);
        $round1BWinners = CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_B_Opening_Match);
        $round1BLosers = CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_B_Opening_Match);
        $round1CWinners = CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_C_Opening_Match);
        $round1CLosers = CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_C_Opening_Match);
        $round1DWinners = CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_D_Opening_Match);
        $round1DLosers = CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_D_Opening_Match);

        $quarterfinalists = new CompetitorPool([
            new CompetitorSourceMatchup(MatchupSignificance::Group_A_Upper_Bracket_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Group_B_Upper_Bracket_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Group_C_Upper_Bracket_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Group_D_Upper_Bracket_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Group_A_Deciding_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Group_B_Deciding_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Group_C_Deciding_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Group_D_Deciding_Match),
        ]);

        return [
            [
                new ProgressionRule(MatchupSignificance::Group_A_Opening_Match, 1, $groupA, $groupA),
                new ProgressionRule(MatchupSignificance::Group_A_Opening_Match, 1, $groupA, $groupA),
                new ProgressionRule(MatchupSignificance::Group_B_Opening_Match, 1, $groupB, $groupB),
                new ProgressionRule(MatchupSignificance::Group_B_Opening_Match, 1, $groupB, $groupB),
                new ProgressionRule(MatchupSignificance::Group_C_Opening_Match, 1, $groupC, $groupC),
                new ProgressionRule(MatchupSignificance::Group_C_Opening_Match, 1, $groupC, $groupC),
                new ProgressionRule(MatchupSignificance::Group_D_Opening_Match, 1, $groupD, $groupD),
                new ProgressionRule(MatchupSignificance::Group_D_Opening_Match, 1, $groupD, $groupD),
            ],
            [
                new ProgressionRule(MatchupSignificance::Group_A_Upper_Bracket_Match, 1, $round1AWinners, $round1AWinners),
                new ProgressionRule(MatchupSignificance::Group_A_Lower_Bracket_Match, 3, $round1ALosers, $round1ALosers),
                new ProgressionRule(MatchupSignificance::Group_B_Upper_Bracket_Match, 1, $round1BWinners, $round1BWinners),
                new ProgressionRule(MatchupSignificance::Group_B_Lower_Bracket_Match, 3, $round1BLosers, $round1BLosers),
                new ProgressionRule(MatchupSignificance::Group_C_Upper_Bracket_Match, 1, $round1CWinners, $round1CWinners),
                new ProgressionRule(MatchupSignificance::Group_C_Lower_Bracket_Match, 3, $round1CLosers, $round1CLosers),
                new ProgressionRule(MatchupSignificance::Group_D_Upper_Bracket_Match, 1, $round1DWinners, $round1DWinners),
                new ProgressionRule(MatchupSignificance::Group_D_Lower_Bracket_Match, 3, $round1DLosers, $round1DLosers),
            ],
            [
                new ProgressionRule(MatchupSignificance::Group_A_Deciding_Match, 3, CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_A_Upper_Bracket_Match), CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_A_Lower_Bracket_Match)),
                new ProgressionRule(MatchupSignificance::Group_B_Deciding_Match, 3, CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_B_Upper_Bracket_Match), CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_B_Lower_Bracket_Match)),
                new ProgressionRule(MatchupSignificance::Group_C_Deciding_Match, 3, CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_C_Upper_Bracket_Match), CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_C_Lower_Bracket_Match)),
                new ProgressionRule(MatchupSignificance::Group_D_Deciding_Match, 3, CompetitorPool::fromMatchupLoser(MatchupSignificance::Group_D_Upper_Bracket_Match), CompetitorPool::fromMatchupWinner(MatchupSignificance::Group_D_Lower_Bracket_Match)),
            ],
            [
                new ProgressionRule(MatchupSignificance::Quarterfinal_1, 3, $quarterfinalists, $quarterfinalists),
                new ProgressionRule(MatchupSignificance::Quarterfinal_2, 3, $quarterfinalists, $quarterfinalists),
                new ProgressionRule(MatchupSignificance::Quarterfinal_3, 3, $quarterfinalists, $quarterfinalists),
                new ProgressionRule(MatchupSignificance::Quarterfinal_4, 3, $quarterfinalists, $quarterfinalists),
            ],
            [
                new ProgressionRule(MatchupSignificance::Semifinal_1, 3, CompetitorPool::fromMatchupWinner(MatchupSignificance::Quarterfinal_1), CompetitorPool::fromMatchupWinner(MatchupSignificance::Quarterfinal_2)),
                new ProgressionRule(MatchupSignificance::Semifinal_2, 3, CompetitorPool::fromMatchupWinner(MatchupSignificance::Quarterfinal_3), CompetitorPool::fromMatchupWinner(MatchupSignificance::Quarterfinal_4)),
            ],
            [
                new ProgressionRule(MatchupSignificance::Grand_Final, 5, CompetitorPool::fromMatchupWinner(MatchupSignificance::Semifinal_1), CompetitorPool::fromMatchupWinner(MatchupSignificance::Semifinal_2)),
            ],
        ];
    }
}
