<?php

namespace App\Console\Commands;

use App\Jobs\ImportGenresJob;
use App\Jobs\ImportMoviesJob;
use App\Jobs\ImportSeriesJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class ImportTmdbCommand extends Command
{
    protected $signature = 'tmdb:import';

    protected $description = 'Import movies, series and genres from TMDB API';

    public function handle(): void
    {
        Bus::chain([
            new ImportGenresJob,
            new ImportMoviesJob,
            new ImportSeriesJob,
        ])->dispatch();

        $this->info('TMDB import jobs dispatched successfully.');
    }
}
