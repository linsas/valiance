<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Player;

/** @extends Factory<Player> */
class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition()
    {
        return [
            'alias' => $this->faker->userName,
        ];
    }
}
