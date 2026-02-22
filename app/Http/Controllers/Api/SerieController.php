<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SerieResource;
use App\Models\Serie;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SerieController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return SerieResource::collection(
            Serie::with('genres')->paginate()
        );
    }

    public function show(Serie $serie): SerieResource
    {
        return new SerieResource($serie->load('genres'));
    }
}
