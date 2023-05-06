<?php

namespace App\Http\Resources;

use App\Models\Player;
use App\Models\PlayerTeamHistory;
use App\Models\TournamentTeamPlayer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Player */
class PlayerResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'alias' => $this->alias,
            'team' => ($this->history->last() == null || $this->history->last()->team == null) ? null : [
                'id' => $this->history->last()->team->id,
                'name' => $this->history->last()->team->name,
            ],
            'history' => $this->history->reverse()->map(fn (PlayerTeamHistory $entry) => [
                'date' => $entry->date_since,
                'team' => $entry->team == null ? null : [
                    'id' => $entry->team->id,
                    'name' => $entry->team->name,
                ],
            ])->toArray(),
            'participations' => $this->tournamentTeamPlayers->map(fn (TournamentTeamPlayer $participantPlayer) => [
                'team' => [
                    'id' => $participantPlayer->tournamentTeam->fk_team,
                    'name' => $participantPlayer->tournamentTeam->name,
                ],
                'tournament' => [
                    'id' => $participantPlayer->tournamentTeam->tournament->id,
                    'name' => $participantPlayer->tournamentTeam->tournament->name,
                ],
            ])->toArray(),
        ];
    }
}
