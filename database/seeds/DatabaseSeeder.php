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

        factory(Tournament::class, 15)->create()->each(function ($tournament) {
            $allTeams = \App\Models\Team::inRandomOrder()->get();
            for ($i = 0; $i < 8; $i++) { // fills 8 teams per tournament
                $team = $allTeams[$i];
                $participant = \App\Models\TournamentTeam::create([
                    'fk_tournament' => $tournament->id,
                    'fk_team' => $team->id,
                    'name' => $team->name,
                    'seed' => $i + 1,
                ]);
                for ($j = 0; $j < $team->players->count(); $j++) {
                    $player = $team->players[$j];
                    \App\Models\TournamentTeamPlayer::create([
                        'fk_player' => $player->id,
                        'fk_tournament_team' => $participant->id,
                    ]);
                }
            }
        });

        factory(Tournament::class, 5)->create();
    }
}
