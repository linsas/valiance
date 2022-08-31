<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MatchupResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => $item->id,
                'team1' => $item->team1->name,
                'team2' => $item->team2->name,
                'tournament' => $item->round->tournament->name,
                'maps' => $item->games->map(function ($game) {
                    return $game->map;
                }),
            ];
        })->toArray();
    }
}
