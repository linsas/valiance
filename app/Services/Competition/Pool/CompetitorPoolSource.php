<?php

namespace App\Services\Competition\Pool;

use App\Models\Tournament;
use Illuminate\Support\Collection;

abstract class CompetitorPoolSource
{
    public abstract function collectParticipants(Tournament $tournament): Collection;
}
