<?php

namespace App\Jobs;

use App\Models\Genre;
use App\Models\Serie;
use App\Services\TmdbService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportSeriesJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    private const TOTAL = 10;

    public function handle(TmdbService $tmdb): void
    {
        $series = $tmdb->getPopularSeries();

        foreach (array_slice($series, 0, self::TOTAL) as $data) {
            $localeEn = $data['_locales']['en'] ?? [];

            $serie = Serie::updateOrCreate(
                ['tmdb_id' => $data['tmdb_id']],
                [
                    'name' => [
                        'en' => $data['_locales']['en']['name'] ?? '',
                        'pl' => $data['_locales']['pl']['name'] ?? '',
                        'de' => $data['_locales']['de']['name'] ?? '',
                    ],
                    'overview' => [
                        'en' => $data['_locales']['en']['overview'] ?? '',
                        'pl' => $data['_locales']['pl']['overview'] ?? '',
                        'de' => $data['_locales']['de']['overview'] ?? '',
                    ],
                    'original_name' => $localeEn['original_name'] ?? '',
                    'poster_path'   => $localeEn['poster_path'] ?? null,
                    'backdrop_path' => $localeEn['backdrop_path'] ?? null,
                    'first_air_date' => $localeEn['first_air_date'] ?: null,
                    'vote_average'  => $localeEn['vote_average'] ?? 0,
                    'vote_count'    => $localeEn['vote_count'] ?? 0,
                    'popularity'    => $localeEn['popularity'] ?? 0,
                ]
            );

            $genreIds = Genre::whereIn('tmdb_id', $localeEn['genre_ids'] ?? [])->pluck('id');
            $serie->genres()->sync($genreIds);
        }
    }

    public function failed(Throwable $e): void
    {
        Log::error('ImportSeriesJob failed', [
            'message' => $e->getMessage(),
        ]);
    }
}
