<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use App\Services\Competition\TournamentFormat;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentTeamPlayer;
use App\Models\Round;
use App\Models\Matchup;
use App\Models\Game;

$factory->define(Tournament::class, function (Faker $faker) {
    $suffixes = ['Championship', 'Tournament', 'Local Cup', 'Regional Cup'];
    return [
        'name' => $faker->city.' '.$faker->randomElement($suffixes),
        'format' => $faker->randomElement(TournamentFormat::$validFormats),
    ];
});

$factory->afterCreatingState(Tournament::class, 'withAllParticipants', function ($tournament, $faker) {
    $format = TournamentFormat::getFormat($tournament->format);

    $allTeams = \App\Models\Team::inRandomOrder()->get();
    if ($allTeams->count() < $format->teamsNeeded) return; // there are not enough teams to choose from

    for ($i = 0; $i < $format->teamsNeeded; $i++) {
        $team = $allTeams[$i];
        $participant = TournamentTeam::create([
            'fk_tournament' => $tournament->id,
            'fk_team' => $team->id,
            'name' => $faker->boolean(85) ? $team->name : $faker->streetName,
            'seed' => $i + 1,
        ]);
        for ($j = 0; $j < $team->players->count(); $j++) {
            $player = $team->players[$j];
            TournamentTeamPlayer::create([
                'fk_player' => $player->id,
                'fk_tournament_team' => $participant->id,
            ]);
        }
    }
});

$factory->afterCreatingState(Tournament::class, 'withSomeRounds', function ($tournament, $faker) {
    $format = TournamentFormat::getFormat($tournament->format);

    $participants = $tournament->tournamentTeams;
    if ($participants->count() !== $format->teamsNeeded) return; // all participants must be present

    $rules = $format->getRules();
    $numPlayedRounds = $faker->numberBetween(1, count($rules));

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

            $isLastPlayedRound = $i === $numPlayedRounds - 1;
            $mapList = $faker->shuffle(Game::$validMaps);
            $hasMaps = !$isLastPlayedRound || $faker->boolean(75); // don't fill last round maps sometimes

            $match = Matchup::create([
                'fk_round' => $round->id,
                'fk_team1' => $high->id,
                'fk_team2' => $low->id,
                'key' => $pairing->matchupKey,
            ]);

            $score1 = 0;
            $score2 = 0;
            for ($j = 0; $j < $pairing->numGames; $j++) {
                $isSequential = ($score1 + $score2) === $j;
                $isMatchDecisive = $score1 > $pairing->numGames / 2 || $score2 > $pairing->numGames / 2;
                $isGamePlayed = $hasMaps && $isSequential && !$isMatchDecisive && (!$isLastPlayedRound || $faker->boolean); // don't fill all last round scores sometimes

                $gameOutcome = $faker->boolean; // true - team 1 victory; false - team 2 victory
                $randomScore = $faker->numberBetween(0, Game::$roundsPerHalf - 1);

                $game = Game::create([
                    'fk_matchup' => $match->id,
                    'number' => $j + 1,
                    'map' => $hasMaps ? $mapList[$j] : null,
                    'score1' => $isGamePlayed ? ($gameOutcome ? Game::$roundsPerHalf + 1 : $randomScore) : null,
                    'score2' => $isGamePlayed ? (!$gameOutcome ? Game::$roundsPerHalf + 1 : $randomScore) : null,
                ]);

                if ($isGamePlayed) {
                    if ($gameOutcome) $score1++; else $score2++;
                }
            }
        }
    }
});
