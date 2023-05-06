<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @property \Illuminate\Support\Collection<int, TournamentResource> $collection */
class TournamentResourceCollection extends ResourceCollection
{
    /** @return array<string, mixed> */
    public function toArray(Request $request)
    {
        return $this->collection->map(fn (TournamentResource $tournament) => [
            'id' => $tournament->id,
            'name' => $tournament->name,
            'format' => $tournament->format,
        ])->toArray();
    }
}
