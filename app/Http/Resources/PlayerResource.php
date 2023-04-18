<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $alias
 * @property \Illuminate\Database\Eloquent\Collection $history
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
            'team' => ($this->history->last() == null || $this->history->last()->team == null) ? null : [
                'id' => $this->history->last()->team->id,
                'name' => $this->history->last()->team->name,
            ],
            'history' => $this->history->reverse()->map(fn ($entry) => [
                'date' => $entry->date_since,
                'team' => $entry->team == null ? null : [
                    'id' => $entry->team->id,
                    'name' => $entry->team->name,
                ],
            ])->toArray(),
            'participations' => $this->tournamentTeamPlayers->map(fn ($ttp) => [
                'team' => [
                    'id' => $ttp->tournamentTeam->fk_team,
                    'name' => $ttp->tournamentTeam->name,
                ],
                'tournament' => [
                    'id' => $ttp->tournamentTeam->tournament->id,
                    'name' => $ttp->tournamentTeam->tournament->name,
                ],
            ])->toArray(),
        ];
    }
}
