<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_movies_index_returns_paginated_list(): void
    {
        Movie::factory()->count(20)->create();

        $response = $this->getJson('/api/movies');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'title', 'overview', 'genres']],
                'meta' => ['current_page', 'total', 'per_page'],
            ]);
    }

    public function test_movies_index_paginates_correctly(): void
    {
        Movie::factory()->count(20)->create();

        $response = $this->getJson('/api/movies?page=2');

        $response->assertOk()
            ->assertJsonPath('meta.current_page', 2);
    }

    public function test_movies_show_returns_single_movie(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->getJson("/api/movies/{$movie->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $movie->id);
    }

    public function test_movies_show_returns_404_for_missing_movie(): void
    {
        $this->getJson('/api/movies/999')->assertNotFound();
    }

    public function test_movies_show_includes_genres(): void
    {
        $genre = Genre::factory()->create();
        $movie = Movie::factory()->create();
        $movie->genres()->attach($genre);

        $response = $this->getJson("/api/movies/{$movie->id}");

        $response->assertOk()
            ->assertJsonPath('data.genres.0.id', $genre->id);
    }

    public function test_movies_returns_title_in_requested_language(): void
    {
        $movie = Movie::factory()->create([
            'title' => ['en' => 'Inception', 'pl' => 'Incepcja', 'de' => 'Inception'],
        ]);

        $response = $this->getJson("/api/movies/{$movie->id}", ['Accept-Language' => 'pl']);

        $response->assertOk()
            ->assertJsonPath('data.title', 'Incepcja');
    }

    public function test_movies_falls_back_to_english_for_unsupported_language(): void
    {
        $movie = Movie::factory()->create([
            'title' => ['en' => 'Inception', 'pl' => 'Incepcja', 'de' => 'Inception'],
        ]);

        $response = $this->getJson("/api/movies/{$movie->id}", ['Accept-Language' => 'fr']);

        $response->assertOk()
            ->assertJsonPath('data.title', 'Inception');
    }
}
