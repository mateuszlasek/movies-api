<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Genre extends Model
{
    use HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = ['tmdb_id', 'name'];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class);
    }

    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Serie::class);
    }
}
