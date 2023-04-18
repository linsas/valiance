<?php

namespace App\Values;

enum MatchupOutcome
{
    case Team1_Victory;
    case Team2_Victory;
    case Indeterminate;
}
