<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Team;
use App\Models\Player;
use App\Models\Tournament;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Team::factory()
            ->count(50)
            ->create();

        Player::factory()
            ->count(100)
            ->create();

        $this->call(PlayerTeamHistorySeeder::class);

        $this->call(CompetitionSeeder::class);

        Tournament::factory()
            ->state(new Sequence(
                ['format' => 1],
                ['format' => 2],
                ['format' => 3],
                ['format' => 4],
                ['format' => 5],
            ))
            ->count(5)
            ->create()
        ;
    }
}
