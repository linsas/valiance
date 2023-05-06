<?php

namespace App\Http\Resources;

use App\Models\Matchup;
use App\Models\Round;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentTeamPlayer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Tournament */
class TournamentResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'format' => $this->format,
            'participants' => $this->tournamentTeams->map(fn (TournamentTeam $participantTeam) => [
                'name' => $participantTeam->name,
                'team' => [
                    'id' => $participantTeam->fk_team,
                    'name' => $participantTeam->team->name,
                ],
                'players' => $participantTeam->tournamentTeamPlayers->map(fn (TournamentTeamPlayer $participantPlayer) => [
                    'id' => $participantPlayer->fk_player,
                    'alias' => $participantPlayer->player->alias,
                ]),
            ]),
            'rounds' => $this->rounds->map(fn (Round $round) => [
                'number' => $round->number,
                'matchups' => $round->matchups->map(fn (Matchup $matchup) => [
                    'id' => $matchup->id,
                    'significanceKey' => $matchup->significance->value,
                    'team1' => $matchup->team1->name,
                    'team2' => $matchup->team2->name,
                    'score1' => $matchup->getTeam1Score(),
                    'score2' => $matchup->getTeam2Score(),
                ]),
            ]),
        ];
    }
}
