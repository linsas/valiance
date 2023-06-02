<?php

namespace App\Http\Resources;

use App\Models\Map;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

final class MapResourceCollection
{
    /** @param \Illuminate\Support\Collection<int, Map> $collection */
    public static function response(Collection $collection): JsonResponse
    {
        return response()->json([
            'data' => $collection->map(fn (Map $map) => [
                'id' => $map->id,
                'name' => $map->name,
                'color' => $map->color,
            ])->toArray()
        ]);
    }
}
