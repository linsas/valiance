<?php

namespace App\Services\Competition;

use App\Models\Tournament;

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
    private $matchupKey;
    private $isWinner;

    public function __construct(string $matchupKey, bool $isWinner = true)
    {
        $this->matchupKey = $matchupKey;
        $this->isWinner = $isWinner;
    }

    public function collect($tournament)
    {
        $matchups = $tournament->matchups->where('key', $this->matchupKey);
        $collection = collect();
        foreach ($matchups as $matchup) {
            $outcome = $matchup->getOutcome();
            $participant = null;
            if ($outcome === 1) $participant = $this->isWinner ? $matchup->team1 : $matchup->team2;
            if ($outcome === -1) $participant = $this->isWinner ? $matchup->team2 : $matchup->team1;
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
    public static function fromMatchup(string $matchupKey, bool $isWinner = true)
    {
        return new PoolComposite([new PoolSourceMatchup($matchupKey, $isWinner)]);
    }
}

class ProgressionRule
{
    public $matchupKey;
    public $numGames;
    private $highComposite;
    private $lowComposite;

    public function __construct(string $matchupKey, int $numGames, PoolComposite $highComposite, PoolComposite $lowComposite)
    {
        $this->matchupKey = $matchupKey;
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
                new ProgressionRule('sf1', 1, $startingComposite, $startingComposite),
                new ProgressionRule('sf2', 1, $startingComposite, $startingComposite),
            ],
            [
                new ProgressionRule('f', 3, PoolComposite::fromMatchup('sf1'), PoolComposite::fromMatchup('sf2')),
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
                new ProgressionRule('qf1', 1, $startingComposite, $startingComposite),
                new ProgressionRule('qf2', 1, $startingComposite, $startingComposite),
                new ProgressionRule('qf3', 1, $startingComposite, $startingComposite),
                new ProgressionRule('qf4', 1, $startingComposite, $startingComposite),
            ],
            [
                new ProgressionRule('sf1', 1, PoolComposite::fromMatchup('qf1'), PoolComposite::fromMatchup('qf2')),
                new ProgressionRule('sf2', 1, PoolComposite::fromMatchup('qf3'), PoolComposite::fromMatchup('qf4')),
            ],
            [
                new ProgressionRule('f', 3, PoolComposite::fromMatchup('sf1'), PoolComposite::fromMatchup('sf2')),
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

        $round1AWinners = PoolComposite::fromMatchup('ao');
        $round1ALosers = PoolComposite::fromMatchup('ao', false);
        $round1BWinners = PoolComposite::fromMatchup('bo');
        $round1BLosers = PoolComposite::fromMatchup('bo', false);

        $semifinalists = new PoolComposite([
            new PoolSourceMatchup('aw'),
            new PoolSourceMatchup('bw'),
            new PoolSourceMatchup('ad'),
            new PoolSourceMatchup('bd'),
        ]);

        return [
            [
                new ProgressionRule('ao', 1, $groupA, $groupA),
                new ProgressionRule('ao', 1, $groupA, $groupA),
                new ProgressionRule('bo', 1, $groupB, $groupB),
                new ProgressionRule('bo', 1, $groupB, $groupB),
            ],
            [
                new ProgressionRule('aw', 1, $round1AWinners, $round1AWinners),
                new ProgressionRule('al', 3, $round1ALosers, $round1ALosers),
                new ProgressionRule('bw', 1, $round1BWinners, $round1BWinners),
                new ProgressionRule('bl', 3, $round1BLosers, $round1BLosers),
            ],
            [
                new ProgressionRule('ad', 3, PoolComposite::fromMatchup('aw', false), PoolComposite::fromMatchup('al')),
                new ProgressionRule('bd', 3, PoolComposite::fromMatchup('bw', false), PoolComposite::fromMatchup('bl')),
            ],
            [
                new ProgressionRule('sf1', 3, $semifinalists, $semifinalists),
                new ProgressionRule('sf2', 3, $semifinalists, $semifinalists),
            ],
            [
                new ProgressionRule('f', 5, PoolComposite::fromMatchup('sf1'), PoolComposite::fromMatchup('sf2')),
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

        $round1AWinners = PoolComposite::fromMatchup('ao');
        $round1ALosers = PoolComposite::fromMatchup('ao', false);
        $round1BWinners = PoolComposite::fromMatchup('bo');
        $round1BLosers = PoolComposite::fromMatchup('bo', false);
        $round1CWinners = PoolComposite::fromMatchup('co');
        $round1CLosers = PoolComposite::fromMatchup('co', false);
        $round1DWinners = PoolComposite::fromMatchup('do');
        $round1DLosers = PoolComposite::fromMatchup('do', false);

        $quarterfinalists = new PoolComposite([
            new PoolSourceMatchup('aw'),
            new PoolSourceMatchup('bw'),
            new PoolSourceMatchup('cw'),
            new PoolSourceMatchup('dw'),
            new PoolSourceMatchup('ad'),
            new PoolSourceMatchup('bd'),
            new PoolSourceMatchup('cd'),
            new PoolSourceMatchup('dd'),
        ]);

        return [
            [
                new ProgressionRule('ao', 1, $groupA, $groupA),
                new ProgressionRule('ao', 1, $groupA, $groupA),
                new ProgressionRule('bo', 1, $groupB, $groupB),
                new ProgressionRule('bo', 1, $groupB, $groupB),
                new ProgressionRule('co', 1, $groupC, $groupC),
                new ProgressionRule('co', 1, $groupC, $groupC),
                new ProgressionRule('do', 1, $groupD, $groupD),
                new ProgressionRule('do', 1, $groupD, $groupD),
            ],
            [
                new ProgressionRule('aw', 1, $round1AWinners, $round1AWinners),
                new ProgressionRule('al', 3, $round1ALosers, $round1ALosers),
                new ProgressionRule('bw', 1, $round1BWinners, $round1BWinners),
                new ProgressionRule('bl', 3, $round1BLosers, $round1BLosers),
                new ProgressionRule('cw', 1, $round1CWinners, $round1CWinners),
                new ProgressionRule('cl', 3, $round1CLosers, $round1CLosers),
                new ProgressionRule('dw', 1, $round1DWinners, $round1DWinners),
                new ProgressionRule('dl', 3, $round1DLosers, $round1DLosers),
            ],
            [
                new ProgressionRule('ad', 3, PoolComposite::fromMatchup('aw', false), PoolComposite::fromMatchup('al')),
                new ProgressionRule('bd', 3, PoolComposite::fromMatchup('bw', false), PoolComposite::fromMatchup('bl')),
                new ProgressionRule('cd', 3, PoolComposite::fromMatchup('cw', false), PoolComposite::fromMatchup('cl')),
                new ProgressionRule('dd', 3, PoolComposite::fromMatchup('dw', false), PoolComposite::fromMatchup('dl')),
            ],
            [
                new ProgressionRule('qf1', 3, $quarterfinalists, $quarterfinalists),
                new ProgressionRule('qf2', 3, $quarterfinalists, $quarterfinalists),
                new ProgressionRule('qf3', 3, $quarterfinalists, $quarterfinalists),
                new ProgressionRule('qf4', 3, $quarterfinalists, $quarterfinalists),
            ],
            [
                new ProgressionRule('sf1', 3, PoolComposite::fromMatchup('qf1'), PoolComposite::fromMatchup('qf2')),
                new ProgressionRule('sf2', 3, PoolComposite::fromMatchup('qf3'), PoolComposite::fromMatchup('qf4')),
            ],
            [
                new ProgressionRule('f', 5, PoolComposite::fromMatchup('sf1'), PoolComposite::fromMatchup('sf2')),
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

        $challengers10 = PoolComposite::fromMatchup('c00');
        $challengers01 = PoolComposite::fromMatchup('c00', false);

        $challengers20 = PoolComposite::fromMatchup('c10');
        $challengers11 = new PoolComposite([new PoolSourceMatchup('c01'), new PoolSourceMatchup('c10', false),]);
        $challengers02 = PoolComposite::fromMatchup('c01', false);

        $challengers21 = new PoolComposite([new PoolSourceMatchup('c11'), new PoolSourceMatchup('c20', false),]);
        $challengers12 = new PoolComposite([new PoolSourceMatchup('c02'), new PoolSourceMatchup('c11', false),]);

        $challengers22 = new PoolComposite([new PoolSourceMatchup('c12'), new PoolSourceMatchup('c21', false),]);

        // legends' stage
        $legendsStage = new PoolComposite([$bestEight, new PoolSourceMatchup('c20'), new PoolSourceMatchup('c21'), new PoolSourceMatchup('c22'),]);

        $legends10 = PoolComposite::fromMatchup('l00');
        $legends01 = PoolComposite::fromMatchup('l00', false);

        $legends20 = PoolComposite::fromMatchup('l10');
        $legends11 = new PoolComposite([new PoolSourceMatchup('l01'), new PoolSourceMatchup('l10', false),]);
        $legends02 = PoolComposite::fromMatchup('l01', false);

        $legends21 = new PoolComposite([new PoolSourceMatchup('l11'), new PoolSourceMatchup('l20', false),]);
        $legends12 = new PoolComposite([new PoolSourceMatchup('l02'), new PoolSourceMatchup('l11', false),]);

        $legends22 = new PoolComposite([new PoolSourceMatchup('l12'), new PoolSourceMatchup('l21', false),]);

        // final stage
        $playoffsStage = new PoolComposite([new PoolSourceMatchup('l20'), new PoolSourceMatchup('l21'), new PoolSourceMatchup('l22'),]);

        return [
            // CHALLENGERS' STAGE
            [
                new ProgressionRule('c00', 1, $challengersStage, $challengersStage),
                new ProgressionRule('c00', 1, $challengersStage, $challengersStage),
                new ProgressionRule('c00', 1, $challengersStage, $challengersStage),
                new ProgressionRule('c00', 1, $challengersStage, $challengersStage),
                new ProgressionRule('c00', 1, $challengersStage, $challengersStage),
                new ProgressionRule('c00', 1, $challengersStage, $challengersStage),
                new ProgressionRule('c00', 1, $challengersStage, $challengersStage),
                new ProgressionRule('c00', 1, $challengersStage, $challengersStage),
            ],
            [
                new ProgressionRule('c10', 1, $challengers10, $challengers10),
                new ProgressionRule('c10', 1, $challengers10, $challengers10),
                new ProgressionRule('c10', 1, $challengers10, $challengers10),
                new ProgressionRule('c10', 1, $challengers10, $challengers10),
                new ProgressionRule('c01', 1, $challengers01, $challengers01),
                new ProgressionRule('c01', 1, $challengers01, $challengers01),
                new ProgressionRule('c01', 1, $challengers01, $challengers01),
                new ProgressionRule('c01', 1, $challengers01, $challengers01),
            ],
            [
                new ProgressionRule('c20', 1, $challengers20, $challengers20),
                new ProgressionRule('c20', 1, $challengers20, $challengers20),
                new ProgressionRule('c11', 1, $challengers11, $challengers11),
                new ProgressionRule('c11', 1, $challengers11, $challengers11),
                new ProgressionRule('c11', 1, $challengers11, $challengers11),
                new ProgressionRule('c11', 1, $challengers11, $challengers11),
                new ProgressionRule('c02', 3, $challengers02, $challengers02),
                new ProgressionRule('c02', 3, $challengers02, $challengers02),
            ],
            [
                new ProgressionRule('c21', 1, $challengers21, $challengers21),
                new ProgressionRule('c21', 1, $challengers21, $challengers21),
                new ProgressionRule('c21', 1, $challengers21, $challengers21),
                new ProgressionRule('c12', 3, $challengers12, $challengers12),
                new ProgressionRule('c12', 3, $challengers12, $challengers12),
                new ProgressionRule('c12', 3, $challengers12, $challengers12),
            ],
            [
                new ProgressionRule('c22', 3, $challengers22, $challengers22),
                new ProgressionRule('c22', 3, $challengers22, $challengers22),
                new ProgressionRule('c22', 3, $challengers22, $challengers22),
            ],
            // LEGENDS' STAGE
            [
                new ProgressionRule('l00', 1, $legendsStage, $legendsStage),
                new ProgressionRule('l00', 1, $legendsStage, $legendsStage),
                new ProgressionRule('l00', 1, $legendsStage, $legendsStage),
                new ProgressionRule('l00', 1, $legendsStage, $legendsStage),
                new ProgressionRule('l00', 1, $legendsStage, $legendsStage),
                new ProgressionRule('l00', 1, $legendsStage, $legendsStage),
                new ProgressionRule('l00', 1, $legendsStage, $legendsStage),
                new ProgressionRule('l00', 1, $legendsStage, $legendsStage),
            ],
            [
                new ProgressionRule('l10', 1, $legends10, $legends10),
                new ProgressionRule('l10', 1, $legends10, $legends10),
                new ProgressionRule('l10', 1, $legends10, $legends10),
                new ProgressionRule('l10', 1, $legends10, $legends10),
                new ProgressionRule('l01', 1, $legends01, $legends01),
                new ProgressionRule('l01', 1, $legends01, $legends01),
                new ProgressionRule('l01', 1, $legends01, $legends01),
                new ProgressionRule('l01', 1, $legends01, $legends01),
            ],
            [
                new ProgressionRule('l20', 1, $legends20, $legends20),
                new ProgressionRule('l20', 1, $legends20, $legends20),
                new ProgressionRule('l11', 1, $legends11, $legends11),
                new ProgressionRule('l11', 1, $legends11, $legends11),
                new ProgressionRule('l11', 1, $legends11, $legends11),
                new ProgressionRule('l11', 1, $legends11, $legends11),
                new ProgressionRule('l02', 3, $legends02, $legends02),
                new ProgressionRule('l02', 3, $legends02, $legends02),
            ],
            [
                new ProgressionRule('l21', 1, $legends21, $legends21),
                new ProgressionRule('l21', 1, $legends21, $legends21),
                new ProgressionRule('l21', 1, $legends21, $legends21),
                new ProgressionRule('l12', 3, $legends12, $legends12),
                new ProgressionRule('l12', 3, $legends12, $legends12),
                new ProgressionRule('l12', 3, $legends12, $legends12),
            ],
            [
                new ProgressionRule('l22', 3, $legends22, $legends22),
                new ProgressionRule('l22', 3, $legends22, $legends22),
                new ProgressionRule('l22', 3, $legends22, $legends22),
            ],
            // PLAYOFFS
            [
                new ProgressionRule('qf1', 3, $playoffsStage, $playoffsStage),
                new ProgressionRule('qf2', 3, $playoffsStage, $playoffsStage),
                new ProgressionRule('qf3', 3, $playoffsStage, $playoffsStage),
                new ProgressionRule('qf4', 3, $playoffsStage, $playoffsStage),
            ],
            [
                new ProgressionRule('sf1', 3, PoolComposite::fromMatchup('qf1'), PoolComposite::fromMatchup('qf2')),
                new ProgressionRule('sf2', 3, PoolComposite::fromMatchup('qf3'), PoolComposite::fromMatchup('qf4')),
            ],
            [
                new ProgressionRule('f', 5, PoolComposite::fromMatchup('sf1'), PoolComposite::fromMatchup('sf2')),
            ],
        ];
    }
}
