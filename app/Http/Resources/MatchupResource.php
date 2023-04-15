<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property \App\Models\TournamentTeam $team1
 * @property \App\Models\TournamentTeam $team2
 * @property \App\Models\Round $round
 * @property string $significance
 * @property \Illuminate\Database\Eloquent\Collection $games
 * @method int getScore1()
 * @method int getScore2()
 */
class MatchupResource extends JsonResource
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
            'team1' => [
                'id' => $this->team1->fk_team,
                'name' => $this->team1->name,
            ],
            'team2' => [
                'id' => $this->team2->fk_team,
                'name' => $this->team2->name,
            ],
            'tournament' => [
                'id' => $this->round->tournament->id,
                'name' => $this->round->tournament->name,
            ],
            'significance' => $this->significance,
            'score1' => $this->getScore1(),
            'score2' => $this->getScore2(),
            'games' => $this->games->sortBy('number')->toArray(),
        ];
    }
}
