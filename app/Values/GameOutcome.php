<?php

namespace App\Values;

enum GameOutcome
{
    case Team1_Victory;
    case Team2_Victory;
    case Indeterminate;
}
