<?php

namespace App\Http\Resources;

use App\Models\Game;
use App\Models\Matchup;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

final class MatchupResourceCollection
{
    /** @param \Illuminate\Support\Collection<int, Matchup> $collection */
    public static function response(Collection $collection): JsonResponse
    {
        return response()->json([
            'data' => $collection->map(fn (Matchup $matchup) => [
                'id' => $matchup->id,
                'team1' => $matchup->team1->name,
                'team2' => $matchup->team2->name,
                'tournament' => $matchup->round->tournament->name,
                'maps' => $matchup->games->sortBy('number')->map(fn (Game $game) => $game->map),
            ])->toArray()
        ]);
    }
}
