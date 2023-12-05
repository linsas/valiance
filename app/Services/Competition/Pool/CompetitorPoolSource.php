<?php

namespace App\Services\Competition\Pool;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use Illuminate\Support\Collection;

abstract class CompetitorPoolSource
{
    /** @return Collection<int, TournamentTeam> */
    public abstract function collectParticipants(Tournament $tournament): Collection;
}
