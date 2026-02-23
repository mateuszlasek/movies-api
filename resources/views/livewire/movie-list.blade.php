<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Movies</h1>
        <div class="flex gap-2">
            @foreach (['en', 'pl', 'de'] as $lang)
                <button
                    wire:click="setLocale('{{ $lang }}')"
                    class="px-3 py-1 rounded text-sm font-medium transition-colors
                        {{ $locale === $lang
                            ? 'bg-gray-800 text-white'
                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                >
                    {{ strtoupper($lang) }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @foreach ($movies as $movie)
            <div class="bg-white rounded-lg shadow p-4 flex gap-4">
                @if ($movie->poster_path)
                    <img
                        src="https://image.tmdb.org/t/p/w92{{ $movie->poster_path }}"
                        alt="{{ $movie->title }}"
                        class="rounded w-16 h-24 object-cover flex-shrink-0"
                    >
                @endif
                <div>
                    <h2 class="font-semibold text-lg">{{ $movie->title }}</h2>
                    <p class="text-sm text-gray-500 mb-1">{{ $movie->release_date?->format('Y') }}</p>
                    <p class="text-sm text-gray-700 line-clamp-2">{{ $movie->overview }}</p>
                    <div class="flex gap-2 mt-2 flex-wrap">
                        @foreach ($movie->genres as $genre)
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                {{ $genre->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 [&_.pagination]:bg-transparent [&_nav]:bg-transparent">
        {{ $movies->links() }}
    </div>
</div>
