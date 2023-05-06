<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $number
 * @property int $fk_tournament
 * @property Tournament $tournament
 * @property Collection<int, Matchup> $matchups
 */
class Round extends Model
{
    public $timestamps = false;
    protected $table = 'round';

    protected $visible = ['number'];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo('App\Models\Tournament', 'fk_tournament');
    }

    public function matchups(): HasMany
    {
        return $this->hasMany('App\Models\Matchup', 'fk_round');
    }
}
