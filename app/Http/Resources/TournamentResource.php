<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property int $format
 * @property \Illuminate\Database\Eloquent\Collection $tournamentTeams
 * @property \Illuminate\Database\Eloquent\Collection $matchups
 */
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
            'participants' => $this->tournamentTeams->map(fn ($tteam) => [
                'name' => $tteam->name,
                'team' => [
                    'id' => $tteam->fk_team,
                    'name' => $tteam->team->name,
                ],
                'players' => $tteam->tournamentTeamPlayers->map(fn ($item) => [
                    'id' => $item->fk_player,
                    'alias' => $item->player->alias,
                ]),
            ]),
            'rounds' => $this->rounds->map(fn ($item) => [
                'number' => $item->number,
                'matchups' => $item->matchups->map(fn ($item) => [
                    'id' => $item->id,
                    'significance' => $item->significance,
                    'team1' => $item->team1->name,
                    'team2' => $item->team2->name,
                    'score1' => $item->getTeam1Score(),
                    'score2' => $item->getTeam2Score(),
                ]),
            ]),
        ];
    }
}
