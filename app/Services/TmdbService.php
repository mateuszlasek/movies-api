<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class TmdbService
{
    private const LOCALES = ['en', 'pl', 'de'];

    private PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::baseUrl('https://api.themoviedb.org/3')
            ->withToken(config('services.tmdb.token'))
            ->throw();
    }

    public function getMovieGenres(): array
    {
        return $this->fetchTranslated(fn (string $locale) => $this->http
            ->get('/genre/movie/list', ['language' => $locale])
            ->json('genres', [])
        );
    }

    public function getPopularMovies(int $page = 1): array
    {
        return $this->fetchTranslated(fn (string $locale) => $this->http
            ->get('/movie/popular', ['language' => $locale, 'page' => $page])
            ->json('results', [])
        );
    }

    public function getPopularSeries(int $page = 1): array
    {
        return $this->fetchTranslated(fn (string $locale) => $this->http
            ->get('/tv/popular', ['language' => $locale, 'page' => $page])
            ->json('results', [])
        );
    }

    private function fetchTranslated(callable $fetcher): array
    {
        $results = [];

        foreach (self::LOCALES as $locale) {
            foreach ($fetcher($locale) as $item) {
                $id = $item['id'];
                $results[$id] ??= ['tmdb_id' => $id];
                $results[$id]['_locales'][$locale] = $item;
            }
        }

        return array_values($results);
    }
}
