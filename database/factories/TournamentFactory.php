<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\Competition\TournamentFormat;
use App\Models\Tournament;

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
}
