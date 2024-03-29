<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Container\Container;
use Carbon\CarbonImmutable;
use Faker\Generator;
use App\Models\Team;
use App\Models\Player;
use App\Models\PlayerTeamHistory;

class PlayerTeamHistorySeeder extends Seeder
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Container::getInstance()->make(Generator::class);
    }

    public function run(): void
    {
        $teams = Team::all();

        foreach (Player::all() as $player) {
            $numberOfTransfers = $this->faker->numberBetween(0, 7);
            $previousTeamId = null;

            for ($i = 0; $i < $numberOfTransfers; $i++) {
                $isNextTeamNotNull = $this->faker->boolean(75);
                $isPreviousTeamNull = $previousTeamId == null;
                $team = ($isPreviousTeamNull || $isNextTeamNotNull) ? $teams->random() : null;

                if ($team->id === $previousTeamId) $team = null; // rare, but not impossible

                $daysAgo = 14 * ($numberOfTransfers - $i - 1) + $this->faker->numberBetween(0, 13);
                $date = CarbonImmutable::now()->addDays(-$daysAgo)->format('Y-m-d');

                PlayerTeamHistory::create([
                    'fk_player' => $player->id,
                    'fk_team' => $team->id,
                    'date_since' => $date,
                    'name' => $team->name,
                ]);
                $previousTeamId = $team->id;
            }
        }
    }
}
