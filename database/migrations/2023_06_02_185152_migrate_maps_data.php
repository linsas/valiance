<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateMapsData extends Migration
{
    /** @var array<array<string, string>> */
    private $maps = [
        ['name' => 'Bind', 'key' => 'bind', 'color' => 'hsl(50, 90%, 60%)',],
        ['name' => 'Haven', 'key' => 'haven', 'color' => 'hsl(20, 90%, 60%)',],
        ['name' => 'Split', 'key' => 'split', 'color' => 'hsl(180, 90%, 60%)',],
        ['name' => 'Ascent', 'key' => 'ascent', 'color' => 'hsl(210, 90%, 60%)',],
        ['name' => 'Icebox', 'key' => 'icebox', 'color' => 'hsl(240, 90%, 60%)',],
        ['name' => 'Breeze', 'key' => 'breeze', 'color' => 'hsl(80, 90%, 60%)',],
        ['name' => 'Fracture', 'key' => 'fracture', 'color' => 'hsl(120, 90%, 60%)',],
        ['name' => 'Pearl', 'key' => 'pearl', 'color' => 'hsl(150, 90%, 60%)',],
        ['name' => 'Lotus', 'key' => 'lotus', 'color' => 'hsl(330, 90%, 60%)',],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->maps as $map) {
            DB::insert('insert into map (id, name, color) values (null, ?, ?)', [$map['name'], $map['color']]);
        }

        $games = DB::select('select * from game');

        foreach ($games as $game) {
            if ($game->map == null) continue;

            $mapId = 0;
            foreach ($this->maps as $mapIndex => $map) {
                if ($map['key'] === $game->map) {
                    $mapId = $mapIndex + 1;
                }
            }
            if ($mapId === 0) continue;

            DB::update('update game set fk_map = ? where id = ?', [$mapId, $game->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $games = DB::select('select * from game');

        foreach ($games as $game) {
            if ($game->fk_map == null) continue;
            if ($game->fk_map > count($this->maps)) continue;

            $map = $this->maps[$game->fk_map - 1];

            DB::update('update game set map = ? where id = ?', [$map['key'], $game->id]);
        }
    }
}
