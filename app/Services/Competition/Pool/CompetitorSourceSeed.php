<?php

namespace App\Services\Competition\Pool;

use App\Models\Tournament;
use Illuminate\Support\Collection;

class CompetitorSourceSeed extends CompetitorPoolSource
{
    private array $seeds;

    public function __construct(array $seeds = [])
    {
        $this->seeds = $seeds;
    }

    public function collectParticipants(Tournament $tournament): Collection
    {
        return $tournament->tournamentTeams->whereIn('seed', $this->seeds);
    }
}
