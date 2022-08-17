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
        'format' => $faker->numberBetween(1, 5),
    ];
});
