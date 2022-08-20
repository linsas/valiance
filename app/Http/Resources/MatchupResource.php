<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'key' => $this->key,
            'score1' => $this->getScore1(),
            'score2' => $this->getScore2(),
            'games' => $this->games->sortBy('number')->toArray(),
        ];
    }
}
