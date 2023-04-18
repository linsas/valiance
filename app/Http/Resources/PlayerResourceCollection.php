<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PlayerResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(fn ($item) => [
            'id' => $item->id,
            'alias' => $item->alias,
            'team' => ($item->history->last() == null || $item->history->last()->team == null) ? null : [
                'id' => $item->history->last()->team->id,
                'name' => $item->history->last()->team->name,
            ],
        ])->toArray();
    }
}
