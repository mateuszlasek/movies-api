<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tmdb_id'        => $this->faker->unique()->numberBetween(1, 100000),
            'title'          => [
                'en' => $this->faker->sentence(3),
                'pl' => $this->faker->sentence(3),
                'de' => $this->faker->sentence(3),
            ],
            'overview'       => [
                'en' => $this->faker->paragraph(),
                'pl' => $this->faker->paragraph(),
                'de' => $this->faker->paragraph(),
            ],
            'original_title' => $this->faker->sentence(3),
            'poster_path'    => '/test.jpg',
            'backdrop_path'  => '/test-backdrop.jpg',
            'release_date'   => $this->faker->date(),
            'vote_average'   => $this->faker->randomFloat(2, 0, 10),
            'vote_count'     => $this->faker->numberBetween(0, 10000),
            'popularity'     => $this->faker->randomFloat(4, 0, 1000),
        ];
    }
}
