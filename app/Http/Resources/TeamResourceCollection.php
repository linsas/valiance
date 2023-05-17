<?php

namespace App\Http\Resources;

use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

final class TeamResourceCollection
{
    /** @param \Illuminate\Support\Collection<int, Team> $collection */
    public static function response(Collection $collection): JsonResponse
    {
        return response()->json([
            'data' => $collection->map(fn (Team $team) => [
                'id' => $team->id,
                'name' => $team->name,
            ])->toArray()
        ]);
    }
}
