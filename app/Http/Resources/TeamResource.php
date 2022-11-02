<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * todo: change to new player history
 * @property \Illuminate\Database\Eloquent\Collection $players
 * @property \Illuminate\Database\Eloquent\Collection $tournamentTeams
 */
class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'players' => $this->players->map(function ($player) {
                return [
                    'id' => $player->id,
                    'alias' => $player->alias,
                ];
            }),
            'participations' => $this->tournamentTeams->map(function ($tt) {
                return [
                    'name' => $tt->name,
                    'tournament' => [
                        'id' => $tt->tournament->id,
                        'name' => $tt->tournament->name,
                    ],
                ];
            }),
        ];
    }
}
