<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Collection $history
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
            'players' => [],
            // 'players' => $this->players->map(fn ($player) => [
            //     'id' => $player->id,
            //     'alias' => $player->alias,
            // ]),
            'history' => $this->history->reverse()->map(fn ($entry) => [
                'date' => $entry->date_since,
                'player' => [
                    'id' => $entry->player->id,
                    'alias' => $entry->player->alias,
                ],
                'team' => $entry->team == null ? null : $entry->team->id,
            ])->toArray(),
            'participations' => $this->tournamentTeams->map(fn ($tteam) => [
                'name' => $tteam->name,
                'tournament' => [
                    'id' => $tteam->tournament->id,
                    'name' => $tteam->tournament->name,
                ],
            ])->toArray(),
        ];
    }
}
