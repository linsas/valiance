<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @property \Illuminate\Support\Collection<int, MatchupResource> $collection */
class MatchupResourceCollection extends ResourceCollection
{
    /** @return array<string, mixed> */
    public function toArray(Request $request)
    {
        return $this->collection->map(fn (MatchupResource $matchup) => [
            'id' => $matchup->id,
            'team1' => $matchup->team1->name,
            'team2' => $matchup->team2->name,
            'tournament' => $matchup->round->tournament->name,
            'maps' => $matchup->games->map(fn ($game) => $game->map),
        ])->toArray();
    }
}
