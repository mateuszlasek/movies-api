<?php

namespace App\Jobs;

use App\Models\Genre;
use App\Services\TmdbService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportGenresJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    public function handle(TmdbService $tmdb): void
    {
        $genres = $tmdb->getMovieGenres();

        foreach ($genres as $genre) {
            Genre::updateOrCreate(
                ['tmdb_id' => $genre['tmdb_id']],
                [
                    'name' => [
                        'en' => $genre['_locales']['en']['name'] ?? '',
                        'pl' => $genre['_locales']['pl']['name'] ?? '',
                        'de' => $genre['_locales']['de']['name'] ?? '',
                    ],
                ]
            );
        }
    }

    public function failed(Throwable $e): void
    {
        Log::error('ImportGenresJob failed', [
            'message' => $e->getMessage(),
        ]);
    }
}
