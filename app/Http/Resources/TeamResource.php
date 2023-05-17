<?php

namespace App\Http\Resources;

use App\Models\Player;
use App\Models\PlayerTeamHistory;
use App\Models\Team;
use App\Models\TournamentTeam;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

final class TeamResource
{
    /**
     * @param \Illuminate\Support\Collection<int, Player> $players
     * @param \Illuminate\Support\Collection<int, PlayerTeamHistory> $history
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
                ])->toArray(),
                'transfers' => $history->sortBy('date')->reverse()->values()->toArray(),
                'participations' => $team->tournamentTeams->map(fn (TournamentTeam $participantTeam) => [
                    'name' => $participantTeam->name,
                    'tournament' => [
                        'id' => $participantTeam->tournament->id,
                        'name' => $participantTeam->tournament->name,
                    ],
                ])->toArray(),
            ]
        ]);
    }
}
