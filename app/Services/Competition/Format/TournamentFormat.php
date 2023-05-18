<?php

namespace App\Services\Competition\Format;

use App\Services\Competition\ProgressionRule;

abstract class TournamentFormat
{
    /** @return array<array<ProgressionRule>> */
    abstract public function getRules(): array;

    abstract public function getTeamsNeeded(): int;
}
