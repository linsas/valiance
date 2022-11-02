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
        return $this->collection->map(function ($item) {
            return [
                'id' => $item->id,
                'alias' => $item->alias,
                // todo: change to new player history
                'team' => $item->team == null ? null : [
                    'id' => $item->fk_team,
                    'name' => $item->team->name,
                ],
            ];
        })->toArray();
    }
}
