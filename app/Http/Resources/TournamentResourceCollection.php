<?php

namespace App\Http\Resources;

use App\Models\Tournament;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

final class TournamentResourceCollection
{
    /** @param \Illuminate\Support\Collection<int, Tournament> $collection */
    public static function response(Collection $collection): JsonResponse
    {
        return response()->json([
            'data' => $collection->map(fn (Tournament $tournament) => [
                'id' => $tournament->id,
                'name' => $tournament->name,
                'format' => $tournament->format,
            ])->values()->toArray()
        ]);
    }
}
