<?php

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Player;
use App\Models\Tournament;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(Team::class, 50)->create();

        factory(Player::class, 150)->create()->each(function ($player) {
            if (rand(1, 5) <= 4) $player->update([ 'fk_team' => Team::inRandomOrder()->first()->id ]);
        });

        factory(Tournament::class, 10)->create();
    }
}
