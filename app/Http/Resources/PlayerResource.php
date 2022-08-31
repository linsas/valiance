<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $alias
 * @property \App\Models\Team|null $team
 * @property \Illuminate\Database\Eloquent\Collection $tournamentTeamPlayers
 */
class PlayerResource extends JsonResource
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
            'alias' => $this->alias,
            'team' => $this->team == null ? null : [
                'id' => $this->team->id,
                'name' => $this->team->name,
            ],
            'participations' => $this->tournamentTeamPlayers->map(function ($ttp) {
                return [
                    'team' => [
                        'id' => $ttp->tournamentTeam->fk_team,
                        'name' => $ttp->tournamentTeam->name,
                    ],
                    'tournament' => [
                        'id' => $ttp->tournamentTeam->tournament->id,
                        'name' => $ttp->tournamentTeam->tournament->name,
                    ],
                ];
            }),
        ];
    }
}
