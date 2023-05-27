<?php

namespace App\Http\Resources;

use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

final class PlayerResourceCollection
{
    /** @param \Illuminate\Support\Collection<int, Player> $collection */
    public static function response(Collection $collection): JsonResponse
    {
        return response()->json([
            'data' => $collection->map(fn (Player $player) => [
                'id' => $player->id,
                'alias' => $player->alias,
                'team' => ($player->history->last() == null || $player->history->last()->team == null) ? null : [
                    'id' => $player->history->last()->team->id,
                    'name' => $player->history->last()->team->name,
                ],
            ])->values()->toArray()
        ]);
    }
}
