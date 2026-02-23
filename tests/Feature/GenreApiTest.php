<?php

namespace Tests\Feature;

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenreApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_genres_index_returns_paginated_list(): void
    {
        Genre::factory()->count(5)->create();

        $response = $this->getJson('/api/genres');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'name']],
                'meta' => ['current_page', 'total', 'per_page'],
            ]);
    }

    public function test_genres_returns_name_in_requested_language(): void
    {
        Genre::factory()->create([
            'name' => ['en' => 'Action', 'pl' => 'Akcja', 'de' => 'Aktion'],
        ]);

        $response = $this->getJson('/api/genres', ['Accept-Language' => 'pl']);

        $response->assertOk()
            ->assertJsonPath('data.0.name', 'Akcja');
    }
}
