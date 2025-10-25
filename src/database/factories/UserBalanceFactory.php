<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserBalanceFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->unique()->numberBetween(1, 5),
            'balance' => rand(0, 10000),
        ];
    }
}
