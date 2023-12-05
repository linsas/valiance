<?php

namespace App\Services\Competition\Pool;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use Illuminate\Support\Collection;

class CompetitorSourceSeed extends CompetitorPoolSource
{
    /** @var array<int> */
    private array $seeds;

    /** @param array<int> $seeds */
    public function __construct(array $seeds = [])
    {
        $this->seeds = $seeds;
    }

    /** @return Collection<int, TournamentTeam> */
    public function collectParticipants(Tournament $tournament): Collection
    {
        return $tournament->tournamentTeams->whereIn('seed', $this->seeds);
    }
}
