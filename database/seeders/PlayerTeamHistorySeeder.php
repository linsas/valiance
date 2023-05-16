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
        $this->faker = Container::getInstance()->make(Generator::class);;
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $teams = Team::all();

        foreach (Player::all() as $player) {
            $numberOfTransfers = $this->faker->numberBetween(0, 5);
            $previousNull = true;

            $dates = [
                CarbonImmutable::now()->addDays($this->faker->numberBetween(-14, -1))
            ];
            // prepare dates
            for ($i = 1; $i < $numberOfTransfers; $i++) {
                $date = new CarbonImmutable($dates[$i - 1]);
                $dates[$i] = $date->addDays($this->faker->numberBetween(-14, -1));
            }
            $dates = array_reverse($dates);

            for ($i = 0; $i < $numberOfTransfers; $i++) {
                $shouldJoinTeam = $this->faker->boolean(75);
                $teamId = ($previousNull || $shouldJoinTeam) ? $teams->random()->id : null;

                PlayerTeamHistory::create([
                    'fk_player' => $player->id,
                    'fk_team' => $teamId,
                    'date_since' => $dates[$i]->format('Y-m-d'),
                ]);
                $previousNull = ($teamId == null);
            }
        }
    }
}
