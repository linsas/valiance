<?php

namespace App\Services\Competition\Format;

use App\Services\Competition\Pool\CompetitorPool;
use App\Services\Competition\Pool\CompetitorSourceMatchup;
use App\Services\Competition\Pool\CompetitorSourceSeed;
use App\Services\Competition\ProgressionRule;
use App\Values\MatchupSignificance;

class Major24TeamFormat extends TournamentFormat
{
    public function getTeamsNeeded(): int
    {
        return 24;
    }

    public function getRules(): array
    {
        $bestEight = new CompetitorSourceSeed([1, 2, 3, 4, 5, 6, 7, 8]);
        $middleEight = new CompetitorSourceSeed([9, 10, 11, 12, 13, 14, 15, 16]);
        $worstEight = new CompetitorSourceSeed([17, 18, 19, 20, 21, 22, 23, 24]);

        // challengers' stage
        $challengersStage = new CompetitorPool([$middleEight, $worstEight]);

        $challengers10 = CompetitorPool::fromMatchupWinner(MatchupSignificance::Challengers_Stage_Opening_Match);
        $challengers01 = CompetitorPool::fromMatchupLoser(MatchupSignificance::Challengers_Stage_Opening_Match);

        $challengers20 = CompetitorPool::fromMatchupWinner(MatchupSignificance::Challengers_Stage_Upper_Group_Match);
        $challengers11 = new CompetitorPool([new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Lower_Group_Match), new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Upper_Group_Match, false),]);
        $challengers02 = CompetitorPool::fromMatchupLoser(MatchupSignificance::Challengers_Stage_Lower_Group_Match);

        $challengers21 = new CompetitorPool([new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Middle_Group_Match), new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Advancing_Match, false),]);
        $challengers12 = new CompetitorPool([new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Elimination_Match), new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Middle_Group_Match, false),]);

        $challengers22 = new CompetitorPool([new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Second_Elimination_Match), new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Second_Advancing_Match, false),]);

        // legends' stage
        $legendsStage = new CompetitorPool([
            $bestEight,
            new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Advancing_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Second_Advancing_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Challengers_Stage_Deciding_Match),
        ]);

        $legends10 = CompetitorPool::fromMatchupWinner(MatchupSignificance::Legends_Stage_Opening_Match);
        $legends01 = CompetitorPool::fromMatchupLoser(MatchupSignificance::Legends_Stage_Opening_Match);

        $legends20 = CompetitorPool::fromMatchupWinner(MatchupSignificance::Legends_Stage_Upper_Group_Match);
        $legends11 = new CompetitorPool([new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Lower_Group_Match), new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Upper_Group_Match, false),]);
        $legends02 = CompetitorPool::fromMatchupLoser(MatchupSignificance::Legends_Stage_Lower_Group_Match);

        $legends21 = new CompetitorPool([new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Middle_Group_Match), new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Advancing_Match, false),]);
        $legends12 = new CompetitorPool([new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Elimination_Match), new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Middle_Group_Match, false),]);

        $legends22 = new CompetitorPool([new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Second_Elimination_Match), new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Second_Advancing_Match, false),]);

        // final stage
        $playoffsStage = new CompetitorPool([
            new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Advancing_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Second_Advancing_Match),
            new CompetitorSourceMatchup(MatchupSignificance::Legends_Stage_Deciding_Match),
        ]);

        return [
            // CHALLENGERS' STAGE
            [
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Opening_Match, 1, $challengersStage, $challengersStage),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Opening_Match, 1, $challengersStage, $challengersStage),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Opening_Match, 1, $challengersStage, $challengersStage),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Opening_Match, 1, $challengersStage, $challengersStage),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Opening_Match, 1, $challengersStage, $challengersStage),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Opening_Match, 1, $challengersStage, $challengersStage),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Opening_Match, 1, $challengersStage, $challengersStage),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Opening_Match, 1, $challengersStage, $challengersStage),
            ],
            [
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Upper_Group_Match, 1, $challengers10, $challengers10),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Upper_Group_Match, 1, $challengers10, $challengers10),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Upper_Group_Match, 1, $challengers10, $challengers10),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Upper_Group_Match, 1, $challengers10, $challengers10),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Lower_Group_Match, 1, $challengers01, $challengers01),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Lower_Group_Match, 1, $challengers01, $challengers01),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Lower_Group_Match, 1, $challengers01, $challengers01),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Lower_Group_Match, 1, $challengers01, $challengers01),
            ],
            [
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Advancing_Match, 1, $challengers20, $challengers20),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Advancing_Match, 1, $challengers20, $challengers20),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Middle_Group_Match, 1, $challengers11, $challengers11),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Middle_Group_Match, 1, $challengers11, $challengers11),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Middle_Group_Match, 1, $challengers11, $challengers11),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Middle_Group_Match, 1, $challengers11, $challengers11),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Elimination_Match, 3, $challengers02, $challengers02),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Elimination_Match, 3, $challengers02, $challengers02),
            ],
            [
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Second_Advancing_Match, 1, $challengers21, $challengers21),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Second_Advancing_Match, 1, $challengers21, $challengers21),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Second_Advancing_Match, 1, $challengers21, $challengers21),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Second_Elimination_Match, 3, $challengers12, $challengers12),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Second_Elimination_Match, 3, $challengers12, $challengers12),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Second_Elimination_Match, 3, $challengers12, $challengers12),
            ],
            [
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Deciding_Match, 3, $challengers22, $challengers22),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Deciding_Match, 3, $challengers22, $challengers22),
                new ProgressionRule(MatchupSignificance::Challengers_Stage_Deciding_Match, 3, $challengers22, $challengers22),
            ],
            // LEGENDS' STAGE
            [
                new ProgressionRule(MatchupSignificance::Legends_Stage_Opening_Match, 1, $legendsStage, $legendsStage),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Opening_Match, 1, $legendsStage, $legendsStage),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Opening_Match, 1, $legendsStage, $legendsStage),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Opening_Match, 1, $legendsStage, $legendsStage),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Opening_Match, 1, $legendsStage, $legendsStage),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Opening_Match, 1, $legendsStage, $legendsStage),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Opening_Match, 1, $legendsStage, $legendsStage),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Opening_Match, 1, $legendsStage, $legendsStage),
            ],
            [
                new ProgressionRule(MatchupSignificance::Legends_Stage_Upper_Group_Match, 1, $legends10, $legends10),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Upper_Group_Match, 1, $legends10, $legends10),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Upper_Group_Match, 1, $legends10, $legends10),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Upper_Group_Match, 1, $legends10, $legends10),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Lower_Group_Match, 1, $legends01, $legends01),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Lower_Group_Match, 1, $legends01, $legends01),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Lower_Group_Match, 1, $legends01, $legends01),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Lower_Group_Match, 1, $legends01, $legends01),
            ],
            [
                new ProgressionRule(MatchupSignificance::Legends_Stage_Advancing_Match, 1, $legends20, $legends20),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Advancing_Match, 1, $legends20, $legends20),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Middle_Group_Match, 1, $legends11, $legends11),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Middle_Group_Match, 1, $legends11, $legends11),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Middle_Group_Match, 1, $legends11, $legends11),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Middle_Group_Match, 1, $legends11, $legends11),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Elimination_Match, 3, $legends02, $legends02),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Elimination_Match, 3, $legends02, $legends02),
            ],
            [
                new ProgressionRule(MatchupSignificance::Legends_Stage_Second_Advancing_Match, 1, $legends21, $legends21),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Second_Advancing_Match, 1, $legends21, $legends21),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Second_Advancing_Match, 1, $legends21, $legends21),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Second_Elimination_Match, 3, $legends12, $legends12),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Second_Elimination_Match, 3, $legends12, $legends12),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Second_Elimination_Match, 3, $legends12, $legends12),
            ],
            [
                new ProgressionRule(MatchupSignificance::Legends_Stage_Deciding_Match, 3, $legends22, $legends22),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Deciding_Match, 3, $legends22, $legends22),
                new ProgressionRule(MatchupSignificance::Legends_Stage_Deciding_Match, 3, $legends22, $legends22),
            ],
            // PLAYOFFS
            [
                new ProgressionRule(MatchupSignificance::Quarterfinal_1, 3, $playoffsStage, $playoffsStage),
                new ProgressionRule(MatchupSignificance::Quarterfinal_2, 3, $playoffsStage, $playoffsStage),
                new ProgressionRule(MatchupSignificance::Quarterfinal_3, 3, $playoffsStage, $playoffsStage),
                new ProgressionRule(MatchupSignificance::Quarterfinal_4, 3, $playoffsStage, $playoffsStage),
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
