<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'overview' => $this->overview,
            'original_title' => $this->original_title,
            'poster_path' => $this->poster_path,
            'backdrop_path' => $this->backdrop_path,
            'release_date' => $this->release_date?->toDateString(),
            'vote_average' => $this->vote_average,
            'vote_count' => $this->vote_count,
            'popularity' => $this->popularity,
            'genres' => GenreResource::collection($this->whenLoaded('genres')),
        ];
    }
}
