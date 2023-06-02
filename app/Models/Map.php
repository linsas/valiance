<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $color
 */
class Map extends Model
{
    public $timestamps = false;
    protected $table = 'map';

    protected $visible = ['name', 'color'];

    public function games()
    {
        return $this->hasMany('App\Models\Game', 'fk_map');
    }
}
