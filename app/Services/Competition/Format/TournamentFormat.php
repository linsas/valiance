<?php

namespace App\Services\Competition\Format;

use App\Services\Competition\ProgressionRule;

abstract class TournamentFormat
{
    public static array $validFormats = [1, 2, 3, 4, 5];

    /** @return array<array<ProgressionRule>> */
    abstract public function getRules(): array;

    abstract public function getTeamsNeeded(): int;

    public static function getFormat(int $format): TournamentFormat
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
