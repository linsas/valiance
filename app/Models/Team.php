<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Collection $history
 * @property \Illuminate\Database\Eloquent\Collection $tournamentTeams
 */
class Team extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'team';

    protected $visible = ['id', 'name'];

    public function history(): HasMany
    {
        return $this->hasMany('App\Models\PlayerTeamHistory', 'fk_team');
    }

    public function tournamentTeams(): HasMany
    {
        return $this->hasMany('App\Models\TournamentTeam', 'fk_team');
    }
}
