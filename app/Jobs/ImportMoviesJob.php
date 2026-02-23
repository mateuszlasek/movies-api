<?php

namespace App\Jobs;

use App\Models\Genre;
use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportMoviesJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 300;

    private const TOTAL = 50;

    public function handle(TmdbService $tmdb): void
    {
        $imported = 0;
        $page = 1;

        while ($imported < self::TOTAL) {
            $movies = $tmdb->getPopularMovies($page++);

            if (empty($movies)) {
                break;
            }

            foreach ($movies as $data) {
                if ($imported >= self::TOTAL) {
                    break;
                }

                $localeEn = $data['_locales']['en'] ?? [];

                $movie = Movie::updateOrCreate(
                    ['tmdb_id' => $data['tmdb_id']],
                    [
                        'title' => [
                            'en' => $data['_locales']['en']['title'] ?? '',
                            'pl' => $data['_locales']['pl']['title'] ?? '',
                            'de' => $data['_locales']['de']['title'] ?? '',
                        ],
                        'overview' => [
                            'en' => $data['_locales']['en']['overview'] ?? '',
                            'pl' => $data['_locales']['pl']['overview'] ?? '',
                            'de' => $data['_locales']['de']['overview'] ?? '',
                        ],
                        'original_title' => $localeEn['original_title'] ?? '',
                        'poster_path' => $localeEn['poster_path'] ?? null,
                        'backdrop_path' => $localeEn['backdrop_path'] ?? null,
                        'release_date' => $localeEn['release_date'] ?: null,
                        'vote_average' => $localeEn['vote_average'] ?? 0,
                        'vote_count' => $localeEn['vote_count'] ?? 0,
                        'popularity' => $localeEn['popularity'] ?? 0,
                    ]
                );

                $genreIds = Genre::whereIn('tmdb_id', $localeEn['genre_ids'] ?? [])->pluck('id');
                $movie->genres()->sync($genreIds);

                $imported++;
            }
        }
    }

    public function failed(Throwable $e): void
    {
        Log::error('ImportMoviesJob failed', [
            'message' => $e->getMessage(),
        ]);
    }
}
