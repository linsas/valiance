<?php

namespace App\Services\Competition;

use App\Models\Tournament;
use App\Values\MatchupOutcome;
use App\Values\MatchupSignificance;

abstract class PoolSource
{
    public abstract function collect(Tournament $tournament);
}

class PoolSourceSeed extends PoolSource
{
    private $seeds;

    public function __construct($seeds = [])
    {
        $this->seeds = $seeds;
    }

    public function collect($tournament)
    {
        return $tournament->tournamentTeams->whereIn('seed', $this->seeds);
    }
}

class PoolSourceMatchup extends PoolSource
{
    private $matchupSignificance;
    private $isWinner;

    public function __construct(MatchupSignificance $matchupSignificance, bool $isWinner = true)
    {
        $this->matchupSignificance = $matchupSignificance;
        $this->isWinner = $isWinner;
    }

    public function collect($tournament)
    {
        $matchups = $tournament->matchups->where('significance', $this->matchupSignificance);
        $collection = collect();
        foreach ($matchups as $matchup) {
            $outcome = $matchup->getOutcome();
            $participant = null;
            if ($outcome === MatchupOutcome::Team1_Victory) $participant = $this->isWinner ? $matchup->team1 : $matchup->team2;
            if ($outcome === MatchupOutcome::Team2_Victory) $participant = $this->isWinner ? $matchup->team2 : $matchup->team1;
            if ($participant != null) $collection->push($participant);
        }
        return $collection;
    }
}

class PoolComposite
{
    private $sources;
    private $teams;

    public function __construct($sources = [])
    {
        $this->sources = $sources;
    }

    public function fill(Tournament $tournament)
    {
        if ($this->teams != null) return;
        $this->teams = collect();
        foreach ($this->sources as $source) {
            $collected = $source->collect($tournament);
            foreach ($collected as $participant) {
                $this->teams->push($participant);
            }
        }
    }

    public function takeHigh()
    {
        return $this->teams->shift();
    }

    public function takeLow()
    {
        return $this->teams->pop();
    }

    public static function fromSeed(array $seeds)
    {
        return new PoolComposite([new PoolSourceSeed($seeds)]);
    }
    public static function fromMatchup(MatchupSignificance $matchupSignificance, bool $isWinner = true)
    {
        return new PoolComposite([new PoolSourceMatchup($matchupSignificance, $isWinner)]);
    }
}

class ProgressionRule
{
    public $matchupSignificance;
    public $numGames;
    private $highComposite;
    private $lowComposite;

    public function __construct(MatchupSignificance $matchupSignificance, int $numGames, PoolComposite $highComposite, PoolComposite $lowComposite)
    {
        $this->matchupSignificance = $matchupSignificance;
        $this->numGames = $numGames;
        $this->highComposite = $highComposite;
        $this->lowComposite = $lowComposite;
    }

    public function fill($tournament)
    {
        $this->highComposite->fill($tournament);
        $this->lowComposite->fill($tournament);
    }

    public function takeHigh()
    {
        return $this->highComposite->takeHigh();
    }

    public function takeLow()
    {
        return $this->lowComposite->takeLow();
    }
}

abstract class TournamentFormat
{
    public static $validFormats = [1, 2, 3, 4, 5];
    public $teamsNeeded;

    /**
     * @return ProgressionRule[][]
     */
    abstract public function getRules();

    /**
     * @param int $format
     * @return TournamentFormat
     */
    public static function getFormat($format)
    {
        switch ($format) {
            case 1:
                return new SingleElimination4TeamFormat();
            case 2:
                return new SingleElimination8TeamFormat();
            case 3:
                return new Minor8TeamFormat();
            case 4:
                return new Minor16TeamFormat();
            case 5:
                return new Major24TeamFormat();
            default:
                throw new \App\Exceptions\InvalidStateException('Invalid tournament format.'); // this should probably be logged
        }
    }
}

class SingleElimination4TeamFormat extends TournamentFormat
{
    public $teamsNeeded = 4;

    public function getRules()
    {
        $startingComposite = PoolComposite::fromSeed([1, 2, 3, 4]);
        return [
            [
                new ProgressionRule(MatchupSignificance::Semifinal_1, 1, $startingComposite, $startingComposite),
                new ProgressionRule(MatchupSignificance::Semifinal_2, 1, $startingComposite, $startingComposite),
            ],
            [
                new ProgressionRule(MatchupSignificance::Grand_Final, 3, PoolComposite::fromMatchup(MatchupSignificance::Semifinal_1), PoolComposite::fromMatchup(MatchupSignificance::Semifinal_2)),
            ],
        ];
    }
}

class SingleElimination8TeamFormat extends TournamentFormat
{
    public $teamsNeeded = 8;

    public function getRules()
    {
        $startingComposite = PoolComposite::fromSeed([1, 2, 3, 4, 5, 6, 7, 8]);
        return [
            [
                new ProgressionRule(MatchupSignificance::Quarterfinal_1, 1, $startingComposite, $startingComposite),
                new ProgressionRule(MatchupSignificance::Quarterfinal_2, 1, $startingComposite, $startingComposite),
                new ProgressionRule(MatchupSignificance::Quarterfinal_3, 1, $startingComposite, $startingComposite),
                new ProgressionRule(MatchupSignificance::Quarterfinal_4, 1, $startingComposite, $startingComposite),
            ],
            [
                new ProgressionRule(MatchupSignificance::Semifinal_1, 1, PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_1), PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_2)),
                new ProgressionRule(MatchupSignificance::Semifinal_2, 1, PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_3), PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_4)),
            ],
            [
                new ProgressionRule(MatchupSignificance::Grand_Final, 3, PoolComposite::fromMatchup(MatchupSignificance::Semifinal_1), PoolComposite::fromMatchup(MatchupSignificance::Semifinal_2)),
            ],
        ];
    }
}

class Minor8TeamFormat extends TournamentFormat
{
    public $teamsNeeded = 8;

    public function getRules()
    {
        $groupA = PoolComposite::fromSeed([1, 3, 5, 7]);
        $groupB = PoolComposite::fromSeed([2, 4, 6, 8]);

        $round1AWinners = PoolComposite::fromMatchup(MatchupSignificance::Group_A_Opening_Match);
        $round1ALosers = PoolComposite::fromMatchup(MatchupSignificance::Group_A_Opening_Match, false);
        $round1BWinners = PoolComposite::fromMatchup(MatchupSignificance::Group_B_Opening_Match);
        $round1BLosers = PoolComposite::fromMatchup(MatchupSignificance::Group_B_Opening_Match, false);

        $semifinalists = new PoolComposite([
            new PoolSourceMatchup(MatchupSignificance::Group_A_Upper_Bracket_Match),
            new PoolSourceMatchup(MatchupSignificance::Group_B_Upper_Bracket_Match),
            new PoolSourceMatchup(MatchupSignificance::Group_A_Deciding_Match),
            new PoolSourceMatchup(MatchupSignificance::Group_B_Deciding_Match),
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
                new ProgressionRule(MatchupSignificance::Group_A_Deciding_Match, 3, PoolComposite::fromMatchup(MatchupSignificance::Group_A_Upper_Bracket_Match, false), PoolComposite::fromMatchup(MatchupSignificance::Group_A_Lower_Bracket_Match)),
                new ProgressionRule(MatchupSignificance::Group_B_Deciding_Match, 3, PoolComposite::fromMatchup(MatchupSignificance::Group_B_Upper_Bracket_Match, false), PoolComposite::fromMatchup(MatchupSignificance::Group_B_Lower_Bracket_Match)),
            ],
            [
                new ProgressionRule(MatchupSignificance::Semifinal_1, 3, $semifinalists, $semifinalists),
                new ProgressionRule(MatchupSignificance::Semifinal_2, 3, $semifinalists, $semifinalists),
            ],
            [
                new ProgressionRule(MatchupSignificance::Grand_Final, 5, PoolComposite::fromMatchup(MatchupSignificance::Semifinal_1), PoolComposite::fromMatchup(MatchupSignificance::Semifinal_2)),
            ],
        ];
    }
}

class Minor16TeamFormat extends TournamentFormat
{
    public $teamsNeeded = 16;

    public function getRules()
    {
        $groupA = PoolComposite::fromSeed([1, 5, 9,  13]);
        $groupB = PoolComposite::fromSeed([2, 6, 10, 14]);
        $groupC = PoolComposite::fromSeed([3, 7, 11, 15]);
        $groupD = PoolComposite::fromSeed([4, 8, 12, 16]);

        $round1AWinners = PoolComposite::fromMatchup(MatchupSignificance::Group_A_Opening_Match);
        $round1ALosers = PoolComposite::fromMatchup(MatchupSignificance::Group_A_Opening_Match, false);
        $round1BWinners = PoolComposite::fromMatchup(MatchupSignificance::Group_B_Opening_Match);
        $round1BLosers = PoolComposite::fromMatchup(MatchupSignificance::Group_B_Opening_Match, false);
        $round1CWinners = PoolComposite::fromMatchup(MatchupSignificance::Group_C_Opening_Match);
        $round1CLosers = PoolComposite::fromMatchup(MatchupSignificance::Group_C_Opening_Match, false);
        $round1DWinners = PoolComposite::fromMatchup(MatchupSignificance::Group_D_Opening_Match);
        $round1DLosers = PoolComposite::fromMatchup(MatchupSignificance::Group_D_Opening_Match, false);

        $quarterfinalists = new PoolComposite([
            new PoolSourceMatchup(MatchupSignificance::Group_A_Upper_Bracket_Match),
            new PoolSourceMatchup(MatchupSignificance::Group_B_Upper_Bracket_Match),
            new PoolSourceMatchup(MatchupSignificance::Group_C_Upper_Bracket_Match),
            new PoolSourceMatchup(MatchupSignificance::Group_D_Upper_Bracket_Match),
            new PoolSourceMatchup(MatchupSignificance::Group_A_Deciding_Match),
            new PoolSourceMatchup(MatchupSignificance::Group_B_Deciding_Match),
            new PoolSourceMatchup(MatchupSignificance::Group_C_Deciding_Match),
            new PoolSourceMatchup(MatchupSignificance::Group_D_Deciding_Match),
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
                new ProgressionRule(MatchupSignificance::Group_A_Deciding_Match, 3, PoolComposite::fromMatchup(MatchupSignificance::Group_A_Upper_Bracket_Match, false), PoolComposite::fromMatchup(MatchupSignificance::Group_A_Lower_Bracket_Match)),
                new ProgressionRule(MatchupSignificance::Group_B_Deciding_Match, 3, PoolComposite::fromMatchup(MatchupSignificance::Group_B_Upper_Bracket_Match, false), PoolComposite::fromMatchup(MatchupSignificance::Group_B_Lower_Bracket_Match)),
                new ProgressionRule(MatchupSignificance::Group_C_Deciding_Match, 3, PoolComposite::fromMatchup(MatchupSignificance::Group_C_Upper_Bracket_Match, false), PoolComposite::fromMatchup(MatchupSignificance::Group_C_Lower_Bracket_Match)),
                new ProgressionRule(MatchupSignificance::Group_D_Deciding_Match, 3, PoolComposite::fromMatchup(MatchupSignificance::Group_D_Upper_Bracket_Match, false), PoolComposite::fromMatchup(MatchupSignificance::Group_D_Lower_Bracket_Match)),
            ],
            [
                new ProgressionRule(MatchupSignificance::Quarterfinal_1, 3, $quarterfinalists, $quarterfinalists),
                new ProgressionRule(MatchupSignificance::Quarterfinal_2, 3, $quarterfinalists, $quarterfinalists),
                new ProgressionRule(MatchupSignificance::Quarterfinal_3, 3, $quarterfinalists, $quarterfinalists),
                new ProgressionRule(MatchupSignificance::Quarterfinal_4, 3, $quarterfinalists, $quarterfinalists),
            ],
            [
                new ProgressionRule(MatchupSignificance::Semifinal_1, 3, PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_1), PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_2)),
                new ProgressionRule(MatchupSignificance::Semifinal_2, 3, PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_3), PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_4)),
            ],
            [
                new ProgressionRule(MatchupSignificance::Grand_Final, 5, PoolComposite::fromMatchup(MatchupSignificance::Semifinal_1), PoolComposite::fromMatchup(MatchupSignificance::Semifinal_2)),
            ],
        ];
    }
}

class Major24TeamFormat extends TournamentFormat
{
    public $teamsNeeded = 24;

    public function getRules()
    {
        $bestEight = new PoolSourceSeed([1, 2, 3, 4, 5, 6, 7, 8]);
        $middleEight = new PoolSourceSeed([9, 10, 11, 12, 13, 14, 15, 16]);
        $worstEight = new PoolSourceSeed([17, 18, 19, 20, 21, 22, 23, 24]);

        // challengers' stage
        $challengersStage = new PoolComposite([$middleEight, $worstEight]);

        $challengers10 = PoolComposite::fromMatchup(MatchupSignificance::Challengers_Stage_Opening_Match);
        $challengers01 = PoolComposite::fromMatchup(MatchupSignificance::Challengers_Stage_Opening_Match, false);

        $challengers20 = PoolComposite::fromMatchup(MatchupSignificance::Challengers_Stage_Upper_Group_Match);
        $challengers11 = new PoolComposite([new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Lower_Group_Match), new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Upper_Group_Match, false),]);
        $challengers02 = PoolComposite::fromMatchup(MatchupSignificance::Challengers_Stage_Lower_Group_Match, false);

        $challengers21 = new PoolComposite([new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Middle_Group_Match), new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Advancing_Match, false),]);
        $challengers12 = new PoolComposite([new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Elimination_Match), new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Middle_Group_Match, false),]);

        $challengers22 = new PoolComposite([new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Second_Elimination_Match), new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Second_Advancing_Match, false),]);

        // legends' stage
        $legendsStage = new PoolComposite([$bestEight, new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Advancing_Match), new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Second_Advancing_Match), new PoolSourceMatchup(MatchupSignificance::Challengers_Stage_Deciding_Match),]);

        $legends10 = PoolComposite::fromMatchup(MatchupSignificance::Legends_Stage_Opening_Match);
        $legends01 = PoolComposite::fromMatchup(MatchupSignificance::Legends_Stage_Opening_Match, false);

        $legends20 = PoolComposite::fromMatchup(MatchupSignificance::Legends_Stage_Upper_Group_Match);
        $legends11 = new PoolComposite([new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Lower_Group_Match), new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Upper_Group_Match, false),]);
        $legends02 = PoolComposite::fromMatchup(MatchupSignificance::Legends_Stage_Lower_Group_Match, false);

        $legends21 = new PoolComposite([new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Middle_Group_Match), new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Advancing_Match, false),]);
        $legends12 = new PoolComposite([new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Elimination_Match), new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Middle_Group_Match, false),]);

        $legends22 = new PoolComposite([new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Second_Elimination_Match), new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Second_Advancing_Match, false),]);

        // final stage
        $playoffsStage = new PoolComposite([new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Advancing_Match), new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Second_Advancing_Match), new PoolSourceMatchup(MatchupSignificance::Legends_Stage_Deciding_Match),]);

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
                new ProgressionRule(MatchupSignificance::Semifinal_1, 3, PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_1), PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_2)),
                new ProgressionRule(MatchupSignificance::Semifinal_2, 3, PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_3), PoolComposite::fromMatchup(MatchupSignificance::Quarterfinal_4)),
            ],
            [
                new ProgressionRule(MatchupSignificance::Grand_Final, 5, PoolComposite::fromMatchup(MatchupSignificance::Semifinal_1), PoolComposite::fromMatchup(MatchupSignificance::Semifinal_2)),
            ],
        ];
    }
}
