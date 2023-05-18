<?php

namespace App\Models;

use App\Exceptions\InvalidStateException;
use App\Services\Competition\Format\Major24TeamFormat;
use App\Services\Competition\Format\Minor16TeamFormat;
use App\Services\Competition\Format\Minor8TeamFormat;
use App\Services\Competition\Format\SingleElimination4TeamFormat;
use App\Services\Competition\Format\SingleElimination8TeamFormat;
use App\Services\Competition\Format\TournamentFormat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int $id
 * @property string $name
 * @property int $format
 * @property Collection<int, TournamentTeam> $tournamentTeams
 * @property Collection<int, Round> $rounds
 * @property Collection<int, Matchup> $matchups
 */
class Tournament extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tournament';

    protected $visible = ['id', 'name', 'format'];

    /** @var array<int> */
    public static array $validFormats = [1, 2, 3, 4, 5];

    /** @return HasMany<TournamentTeam> */
    public function tournamentTeams(): HasMany
    {
        return $this->hasMany(TournamentTeam::class, 'fk_tournament');
    }

    /** @return HasManyThrough<Matchup> */
    public function matchups(): HasManyThrough
    {
        return $this->hasManyThrough(
            Matchup::class, // the related model we want
            Round::class, // the in-between model
            'fk_tournament', // the foreign key on the in-between model
            'fk_round', // the foreign key on this model
            'id', // the local key on this model
            'id', // the local key on the in-between model
        );
    }

    /** @return HasMany<Round> */
    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class, 'fk_tournament');
    }

    public function getFormat(): TournamentFormat
    {
        switch ($this->format) {
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
                throw new InvalidStateException('Invalid tournament format.');
        }
    }
}
