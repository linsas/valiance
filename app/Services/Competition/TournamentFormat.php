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
        //     case 3:
        //         return new Minor8TeamFormat();
        //     case 4:
        //         return new Minor16TeamFormat();
        //     case 5:
        //         return new Major24TeamFormat();
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
