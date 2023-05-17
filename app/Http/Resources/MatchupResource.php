<?php

namespace App\Http\Resources;

use App\Models\Matchup;
use Illuminate\Http\JsonResponse;

final class MatchupResource
{
    public static function response(Matchup $matchup): JsonResponse
    {
        return response()->json([
            'data' => [
                'id' => $matchup->id,
                'team1' => [
                    'id' => $matchup->team1->fk_team,
                    'name' => $matchup->team1->name,
                ],
                'team2' => [
                    'id' => $matchup->team2->fk_team,
                    'name' => $matchup->team2->name,
                ],
                'tournament' => [
                    'id' => $matchup->round->tournament->id,
                    'name' => $matchup->round->tournament->name,
                ],
                'significance' => $matchup->significance->getRepresentation(),
                'score1' => $matchup->getTeam1Score(),
                'score2' => $matchup->getTeam2Score(),
                'games' => $matchup->games->sortBy('number')->toArray(),
            ]
        ]);
    }
}
