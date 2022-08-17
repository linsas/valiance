<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public $timestamps = false;
    protected $table = 'round';

    protected $visible = ['number'];

    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament', 'fk_tournament');
    }

    public function matchups()
    {
        return $this->hasMany('App\Models\Matchup', 'fk_round');
    }
}
