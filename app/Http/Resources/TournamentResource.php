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
            'matchups' => $this->matchups->map(function ($item) {
                return [
                    'id' => $item->id,
                    'significance' => $item->significance,
                    'team1' => $item->team1->name,
                    'team2' => $item->team2->name,
                    'score1' => $item->getScore1(),
                    'score2' => $item->getScore2(),
                ];
            }),
        ];
    }
}
