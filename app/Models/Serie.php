<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Serie extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['name', 'overview'];

    protected $fillable = [
        'tmdb_id',
        'name',
        'overview',
        'original_name',
        'poster_path',
        'backdrop_path',
        'first_air_date',
        'vote_average',
        'vote_count',
        'popularity',
    ];

    protected function casts(): array
    {
        return [
            'first_air_date' => 'date',
        ];
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }
}
