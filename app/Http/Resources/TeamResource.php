<?php

namespace App\Http\Resources;

use App\Models\PlayerTeamHistory;
use App\Models\Team;
use App\Models\TournamentTeam;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Team */
class TeamResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'history' => $this->history->reverse()->map(fn (PlayerTeamHistory $entry) => [
                'date' => $entry->date_since,
                'player' => [
                    'id' => $entry->player->id,
                    'alias' => $entry->player->alias,
                ],
                'team' => $entry->team == null ? null : $entry->team->id,
            ])->toArray(),
            'participations' => $this->tournamentTeams->map(fn (TournamentTeam $participantTeam) => [
                'name' => $participantTeam->name,
                'tournament' => [
                    'id' => $participantTeam->tournament->id,
                    'name' => $participantTeam->tournament->name,
                ],
            ])->toArray(),
        ];
    }
}
