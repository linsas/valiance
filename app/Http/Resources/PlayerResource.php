<?php

namespace App\Http\Resources;

use App\Models\Player;
use App\Models\PlayerTeamHistory;
use App\Models\TournamentTeamPlayer;
use Illuminate\Http\JsonResponse;

final class PlayerResource
{
    public static function response(Player $player): JsonResponse
    {
        return response()->json([
            'data' => [
                'id' => $player->id,
                'alias' => $player->alias,
                'team' => ($player->history->last() == null || $player->history->last()->team == null) ? null : [
                    'id' => $player->history->last()->team->id,
                    'name' => $player->history->last()->team->name,
                ],
                'history' => $player->history->sortBy('date_since')->reverse()->map(fn (PlayerTeamHistory $entry) => [
                    'date' => $entry->date_since,
                    'team' => $entry->team == null ? null : [
                        'id' => $entry->team->id,
                        'name' => $entry->team->name,
                    ],
                ])->values()->toArray(),
                'participations' => $player->tournamentTeamPlayers->map(fn (TournamentTeamPlayer $participantPlayer) => [
                    'team' => [
                        'id' => $participantPlayer->tournamentTeam->fk_team,
                        'name' => $participantPlayer->tournamentTeam->name,
                    ],
                    'tournament' => [
                        'id' => $participantPlayer->tournamentTeam->tournament->id,
                        'name' => $participantPlayer->tournamentTeam->tournament->name,
                    ],
                ])->toArray(),
            ]
        ]);
    }
}
