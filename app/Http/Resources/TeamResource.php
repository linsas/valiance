<?php

namespace App\Http\Resources;

use App\Models\Player;
use App\Models\Team;
use App\Models\TournamentTeam;
use App\Values\PlayerTransfer;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

final class TeamResource
{
    /**
     * @param \Illuminate\Support\Collection<int, Player> $players
     * @param \Illuminate\Support\Collection<int, PlayerTransfer> $history
     */
    public static function response(Team $team, Collection $players, Collection $history): JsonResponse
    {
        return response()->json([
            'data' => [
                'id' => $team->id,
                'name' => $team->name,
                'players' => $players->map(fn (Player $player) => [
                    'id' => $player->id,
                    'alias' => $player->alias,
                ])->values()->toArray(),
                'transfers' => $history->map(fn (PlayerTransfer $t) => $t->serialize())->sortBy('date')->reverse()->values()->toArray(),
                'participations' => $team->tournamentTeams->map(fn (TournamentTeam $participantTeam) => [
                    'name' => $participantTeam->name,
                    'tournament' => [
                        'id' => $participantTeam->tournament->id,
                        'name' => $participantTeam->tournament->name,
                    ],
                ])->values()->toArray(),
            ]
        ]);
    }
}
