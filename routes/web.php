<?php

use App\Livewire\MovieList;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/movies', MovieList::class);
