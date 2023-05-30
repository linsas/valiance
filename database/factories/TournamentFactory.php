<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tournament;

/** @extends Factory<Tournament> */
class TournamentFactory extends Factory
{
    protected $model = Tournament::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city . ' ' . $this->faker->randomElement(['Championship', 'Tournament', 'Local Cup', 'Regional Cup',]),
            'format' => $this->faker->randomElement(Tournament::$validFormats),
        ];
    }
}
