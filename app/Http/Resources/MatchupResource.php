<?php

namespace App\Http\Resources;

use App\Models\Matchup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Matchup */
class MatchupResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
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
            'significance' => $this->significance->getRepresentation(),
            'score1' => $this->getTeam1Score(),
            'score2' => $this->getTeam2Score(),
            'games' => $this->games->sortBy('number')->toArray(),
        ];
    }
}
