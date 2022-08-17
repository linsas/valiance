<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = false;
    protected $table = 'game';

    protected $fillable = ['fk_matchup', 'number', 'map', 'score1', 'score2'];
    protected $visible = ['map', 'score1', 'score2', 'number'];

    public static $validMaps = ['bind', 'haven', 'split', 'ascent', 'icebox', 'breeze', 'fracture', 'pearl',];
    public static $roundsPerHalf = 12;

    public function matchup()
    {
        return $this->belongsTo('App\Models\Matchup', 'fk_matchup');
    }
}
