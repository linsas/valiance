<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\Competition\TournamentFormat;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentTeamPlayer;
use App\Models\Round;
use App\Models\Matchup;
use App\Models\Game;
use App\Models\Team;
use App\Models\PlayerTeamHistory;

class TournamentFactory extends Factory
{
    protected $model = Tournament::class;

    private $suffixes = ['Championship', 'Tournament', 'Local Cup', 'Regional Cup'];

    public function definition()
    {
        return [
            'name' => $this->faker->city . ' ' . $this->faker->randomElement($this->suffixes),
            'format' => $this->faker->randomElement(TournamentFormat::$validFormats),
        ];
    }

    // maybe (in the future) it would be better to split this tournament factory
    // into one for each format rather than everything in a super abstract factory

    public function withAllParticipants()
    {
        return $this->afterCreatingState(function (Tournament $tournament) {
            $format = TournamentFormat::getFormat($tournament->format);

            $allTeams = Team::inRandomOrder()->get();
            if ($allTeams->count() < $format->teamsNeeded) return; // there are not enough teams to choose from

            $date = new \DateTimeImmutable('-' . rand(0, 30) . ' days'); // a random date to choose the participants from

            for ($i = 0; $i < $format->teamsNeeded; $i++) {
                $team = $allTeams[$i];
                $participant = TournamentTeam::create([
                    'fk_tournament' => $tournament->id,
                    'fk_team' => $team->id,
                    'name' => $this->faker->boolean(65) ? $team->name : $this->faker->streetName,
                    'seed' => $i + 1,
                ]);

                // choose players from when they were in the team
                $history = PlayerTeamHistory::where('date_since', '<=', $date->format('Y-m-d'))->orderBy('date_since', 'desc')->get()->unique('fk_player')->where('fk_team', $team->id);
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
        });
    }

    public function withSomeRounds()
    {
        return $this->afterCreating(function (Tournament $tournament) {
            $format = TournamentFormat::getFormat($tournament->format);

            $participants = $tournament->tournamentTeams;
            if ($participants->count() !== $format->teamsNeeded) return; // all participants must be present

            $rules = $format->getRules();
            $numPlayedRounds = $this->faker->numberBetween(1, count($rules)); // don't play all the rounds, have an incomplete round in the middle

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

                    $matchupMapList = $this->faker->shuffle(Game::$validMaps); // shuffle the maps once per matchup

                    $isLastPlayedRound = $i === $numPlayedRounds - 1;
                    $hasMaps = !$isLastPlayedRound || $this->faker->boolean(75); // if this is the incomplete round, don't fill the map info sometimes

                    $match = Matchup::create([
                        'fk_round' => $round->id,
                        'fk_team1' => $high->id,
                        'fk_team2' => $low->id,
                        'significance' => $pairing->matchupSignificance,
                    ]);

                    $matchScore1 = 0;
                    $matchScore2 = 0;
                    for ($j = 0; $j < $pairing->numGames; $j++) {
                        $isSequential = ($matchScore1 + $matchScore2) === $j; // if the last game was not played, don't play any more games
                        $isMatchDecisive = $matchScore1 > $pairing->numGames / 2 || $matchScore2 > $pairing->numGames / 2; // if enough games have been played to determine outcome, don't play any more
                        $shouldPlay = !$isLastPlayedRound || $this->faker->boolean; // if this is the incomplete round, don't play the game sometimes

                        $isGamePlayed = $hasMaps && $isSequential && !$isMatchDecisive && $shouldPlay;

                        $isTeam1Winner = $this->faker->boolean; // true - team 1 victory; false - team 2 victory
                        $randomScore = $this->faker->numberBetween(0, Game::$roundsPerHalf - 1);

                        Game::create([
                            'fk_matchup' => $match->id,
                            'number' => $j + 1,
                            'map' => $hasMaps ? $matchupMapList[$j] : null,
                            'score1' => $isGamePlayed ? ($isTeam1Winner ? Game::$roundsPerHalf + 1 : $randomScore) : null,
                            'score2' => $isGamePlayed ? (!$isTeam1Winner ? Game::$roundsPerHalf + 1 : $randomScore) : null,
                        ]);

                        if ($isGamePlayed) {
                            if ($isTeam1Winner) $matchScore1++;
                            else $matchScore2++;
                        }
                    }
                }
            }
        });
    }
}
