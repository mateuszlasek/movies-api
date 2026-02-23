<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Movies API</title>
    </head>
    <body>
        <h1>Movies API</h1>
        <ul>
            <li><a href="/api/movies">GET /api/movies</a></li>
            <li><a href="/api/series">GET /api/series</a></li>
            <li><a href="/api/genres">GET /api/genres</a></li>
            <li><a href="/movies">Livewire movie list</a></li>
        </ul>
    </body>
</html>
