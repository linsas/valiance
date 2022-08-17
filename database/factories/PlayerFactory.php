<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use App\Models\Player;

$factory->define(Player::class, function (Faker $faker) {
    return [
        'alias' => $faker->userName,
        'fk_team' => null,
    ];
});
