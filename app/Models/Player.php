<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $alias
 * @property \Illuminate\Database\Eloquent\Collection $history
 * @property \Illuminate\Database\Eloquent\Collection $tournamentTeamPlayers
 */
class Player extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'player';

    protected $visible = ['id', 'alias'];

    public function history(): HasMany
    {
        return $this->hasMany('App\Models\PlayerTeamHistory', 'fk_player');
    }

    public function tournamentTeamPlayers(): HasMany
    {
        return $this->hasMany('App\Models\TournamentTeamPlayer', 'fk_player');
    }
}
