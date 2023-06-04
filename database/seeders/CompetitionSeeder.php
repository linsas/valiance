<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Container\Container;
use Carbon\CarbonImmutable;
use Faker\Generator;
use App\Models\Team;
use App\Models\PlayerTeamHistory;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentTeamPlayer;
use App\Models\Round;
use App\Models\Matchup;
use App\Models\Game;
use App\Models\Map;

class CompetitionSeeder extends Seeder
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Container::getInstance()->make(Generator::class);;
    }

    public function run(): void
    {
        Tournament::factory()
            ->state(new Sequence(
                ['format' => 1],
                ['format' => 2],
                ['format' => 3],
                ['format' => 4],
                ['format' => 5],
            ))
            ->count(15)
            ->create()
            ->each(function (Tournament $tournament) {
                $this->seedParticipants($tournament);
                $this->seedSomeRounds($tournament);
            })
        ;

        Tournament::factory()
            ->state(new Sequence(
                ['format' => 1],
                ['format' => 2],
                ['format' => 3],
                ['format' => 4],
                ['format' => 5],
            ))
            ->count(10)
            ->create()
            ->each(function (Tournament $tournament) {
                $this->seedParticipants($tournament);
            })
        ;
    }

    private function seedParticipants(Tournament $tournament): void
    {
        $format = $tournament->getFormat();

        $allTeams = Team::inRandomOrder()->get();
        if ($allTeams->count() < $format->getTeamsNeeded()) return; // there are not enough teams to choose from

        $date = CarbonImmutable::now()->addDays($this->faker->numberBetween(-30, -1)); // a random date to choose the participants from

        for ($i = 0; $i < $format->getTeamsNeeded(); $i++) {
            $team = $allTeams[$i];
            if ($team == null) continue;

            $participant = TournamentTeam::create([
                'fk_tournament' => $tournament->id,
                'fk_team' => $team->id,
                'name' => $this->faker->boolean(75) ? $team->name : $this->faker->streetName,
                'seed' => $i + 1,
            ]);

            // choose players from when they were in the team
            $history = PlayerTeamHistory::where('date_since', '<=', $date->format('Y-m-d'))
                ->orderBy('date_since', 'desc')
                ->get()
                ->unique('fk_player')
                ->where('fk_team', $team->id)
            ;

            $players = $history->map(function ($entry) {
                return $entry->player;
            })->values();

            for ($j = 0; $j < $players->count(); $j++) {
                $player = $players[$j];
                TournamentTeamPlayer::create([
                    'fk_player' => $player->id,
                    'fk_tournament_team' => $participant->id,
                ]);
            }
        }
    }

    private function seedSomeRounds(Tournament $tournament): void
    {
        $format = $tournament->getFormat();

        $participants = $tournament->tournamentTeams;
        if ($participants->count() !== $format->getTeamsNeeded()) return; // all participants must be present

        $rules = $format->getRules();
        $numPlayedRounds = $this->faker->numberBetween(1, count($rules)); // don't play all the rounds - have an incomplete round in the middle

        for ($i = 0; $i < $numPlayedRounds; $i++) {
            $ruleset = $rules[$i];

            $tournament->load('matchups'); // tournament->matchups is cached; reload every round
            foreach ($ruleset as $pairing) $pairing->fill($tournament);

            $round = Round::create([
                'fk_tournament' => $tournament->id,
                'number' => $i + 1,
            ]);

            foreach ($ruleset as $pairing) {
                $high = $pairing->takeHigh();
                $low = $pairing->takeLow();

                $matchupMapList = $this->faker->shuffle(Map::all()); // shuffle the maps once per matchup

                $isLastPlayedRound = $i === $numPlayedRounds - 1;
                $hasMaps = !$isLastPlayedRound || $this->faker->boolean; // if this is the incomplete round, don't fill the map info sometimes

                $match = Matchup::create([
                    'fk_round' => $round->id,
                    'fk_team1' => $high->id,
                    'fk_team2' => $low->id,
                    'significance' => $pairing->matchupSignificance,
                ]);

                $team1MatchScore = 0;
                $team2MatchScore = 0;
                for ($j = 0; $j < $pairing->numGames; $j++) {
                    $isPreviousPlayed = ($team1MatchScore + $team2MatchScore) === $j; // if the previous game was not played, don't play any more games
                    $isDecisiveAlready = $team1MatchScore > $pairing->numGames / 2 || $team2MatchScore > $pairing->numGames / 2; // if enough games have been played to determine outcome, don't play any more
                    $shouldPlay = !$isLastPlayedRound || $this->faker->boolean; // if this is the incomplete round, don't play the game sometimes

                    $isGamePlayed = $hasMaps && $isPreviousPlayed && !$isDecisiveAlready && $shouldPlay;

                    $isTeam1Winner = $this->faker->boolean; // true - team 1 victory; false - team 2 victory
                    $loserScore = $this->faker->numberBetween(0, Game::$roundsPerHalf - 1);

                    Game::create([
                        'fk_matchup' => $match->id,
                        'number' => $j + 1,
                        'fk_map' => $hasMaps ? $matchupMapList[$j]->id : null,
                        'score1' => $isGamePlayed ? ($isTeam1Winner ? Game::$roundsPerHalf + 1 : $loserScore) : null,
                        'score2' => $isGamePlayed ? (!$isTeam1Winner ? Game::$roundsPerHalf + 1 : $loserScore) : null,
                    ]);

                    if ($isGamePlayed) {
                        if ($isTeam1Winner) $team1MatchScore++;
                        else $team2MatchScore++;
                    }
                }
            }
        }
    }
}
