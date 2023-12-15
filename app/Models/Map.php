<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $color
 */
class Map extends Model
{
    public $timestamps = false;
    protected $table = 'map';

    /** @return HasMany<Game> */
    public function games()
    {
        return $this->hasMany('App\Models\Game', 'fk_map');
    }
}
