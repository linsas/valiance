<?php

namespace App\Models;

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

    public function tournamentTeams(): HasMany
    {
        return $this->hasMany('App\Models\TournamentTeam', 'fk_tournament');
    }

    public function matchups(): HasManyThrough
    {
        return $this->hasManyThrough(
            'App\Models\Matchup', // the related model we want
            'App\Models\Round', // the in-between model
            'fk_tournament', // the foreign key on the in-between model
            'fk_round', // the foreign key on this model
            'id', // the local key on this model
            'id', // the local key on the in-between model
        );
    }

    public function rounds(): HasMany
    {
        return $this->hasMany('App\Models\Round', 'fk_tournament');
    }
}
