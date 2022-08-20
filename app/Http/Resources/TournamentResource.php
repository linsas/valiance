<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
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
            'format' => $this->format,
            'participants' => $this->tournamentTeams->map(function ($tteam) {
                return [
                    'name' => $tteam->name,
                    'team' => [
                        'id' => $tteam->fk_team,
                        'name' => $tteam->team->name,
                    ],
                    'players' => $tteam->tournamentTeamPlayers->map(function ($item) {
                        return [
                            'id' => $item->fk_player,
                            'alias' => $item->player->alias,
                        ];
                    }),
                ];
            }),
        ];
    }
}
