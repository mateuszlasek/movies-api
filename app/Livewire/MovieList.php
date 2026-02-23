<?php

namespace App\Livewire;

use App\Models\Movie;
use Livewire\Component;
use Livewire\WithPagination;

class MovieList extends Component
{
    use WithPagination;

    public string $locale = 'en';

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
        $this->resetPage();
    }

    public function render()
    {
        app()->setLocale($this->locale);

        return view('livewire.movie-list', [
            'movies' => Movie::with('genres')->paginate(10),
        ])->layout('layouts.app');
    }
}
