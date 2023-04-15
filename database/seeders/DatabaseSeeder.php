<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Player;
use App\Models\PlayerTeamHistory;
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
        Team::factory()->count(50)->create();

        Player::factory()->count(100)->create()->each(function ($player) {
            $numTransfers = rand(1, 5);
            $previousTeam = null;
            for ($i = 0; $i < $numTransfers; $i++) {
                $team = Team::inRandomOrder()->get()->reject(function ($value) use ($previousTeam) { return $value == $previousTeam; })->first();
                if (rand(1, 3) === 1 && $i !== 0 && $previousTeam != null) $team = null;

                $numDays = ($numTransfers - $i) * 8 - rand(1, 7);
                $date = new \DateTimeImmutable('-' . $numDays . ' days');

                PlayerTeamHistory::create([
                    'date_since' => $date->format('Y-m-d'),
                    'fk_player' => $player->id,
                    'fk_team' => $team == null ? null : $team->id,
                ]);
                $previousTeam = $team;
            }
        });

        Tournament::factory()->count(15)->withAllParticipants()->withSomeRounds()->create();
        Tournament::factory()->count(7)->withAllParticipants()->create();
        Tournament::factory()->count(3)->withAllParticipants()->create();
    }
}
