<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GenreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tmdb_id' => $this->faker->unique()->numberBetween(1, 10000),
            'name'    => [
                'en' => $this->faker->word(),
                'pl' => $this->faker->word(),
                'de' => $this->faker->word(),
            ],
        ];
    }
}
