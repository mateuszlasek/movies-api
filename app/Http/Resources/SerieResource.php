<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SerieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'overview' => $this->overview,
            'original_name' => $this->original_name,
            'poster_path' => $this->poster_path,
            'backdrop_path' => $this->backdrop_path,
            'first_air_date' => $this->first_air_date?->toDateString(),
            'vote_average' => $this->vote_average,
            'vote_count' => $this->vote_count,
            'popularity' => $this->popularity,
            'genres' => GenreResource::collection($this->whenLoaded('genres')),
        ];
    }
}
