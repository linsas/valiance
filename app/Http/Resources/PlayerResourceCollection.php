<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @property \Illuminate\Support\Collection<int, PlayerResource> $collection */
class PlayerResourceCollection extends ResourceCollection
{
    /** @return array<string, mixed> */
    public function toArray(Request $request)
    {
        return $this->collection->map(fn (PlayerResource $player) => [
            'id' => $player->id,
            'alias' => $player->alias,
            'team' => ($player->history->last() == null || $player->history->last()->team == null) ? null : [
                'id' => $player->history->last()->team->id,
                'name' => $player->history->last()->team->name,
            ],
        ])->toArray();
    }
}
