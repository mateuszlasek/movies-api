<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Serie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SerieApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_series_index_returns_paginated_list(): void
    {
        Serie::factory()->count(20)->create();

        $response = $this->getJson('/api/series');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'name', 'overview', 'genres']],
                'meta' => ['current_page', 'total', 'per_page'],
            ]);
    }

    public function test_series_index_paginates_correctly(): void
    {
        Serie::factory()->count(20)->create();

        $response = $this->getJson('/api/series?page=2');

        $response->assertOk()
            ->assertJsonPath('meta.current_page', 2);
    }

    public function test_series_show_returns_single_serie(): void
    {
        $serie = Serie::factory()->create();

        $response = $this->getJson("/api/series/{$serie->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $serie->id);
    }

    public function test_series_show_returns_404_for_missing_serie(): void
    {
        $this->getJson('/api/series/999')->assertNotFound();
    }

    public function test_series_show_includes_genres(): void
    {
        $genre = Genre::factory()->create();
        $serie = Serie::factory()->create();
        $serie->genres()->attach($genre);

        $response = $this->getJson("/api/series/{$serie->id}");

        $response->assertOk()
            ->assertJsonPath('data.genres.0.id', $genre->id);
    }

    public function test_series_returns_name_in_requested_language(): void
    {
        $serie = Serie::factory()->create([
            'name' => ['en' => 'Breaking Bad', 'pl' => 'Zła seria', 'de' => 'Schlechte Serie'],
        ]);

        $response = $this->getJson("/api/series/{$serie->id}", ['Accept-Language' => 'de']);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Schlechte Serie');
    }
}
