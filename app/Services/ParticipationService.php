<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Exceptions\InvalidStateException;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentTeamPlayer;

class ParticipationService
{
    private PlayerTeamHistoryService $historyService;

    public function __construct(PlayerTeamHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function updateParticipations(array $inputData, int $tournamentId): void
    {
        $tournament = Tournament::findOrFail($tournamentId);
        if ($tournament->rounds->count() > 0) {
            throw new InvalidStateException('Cannot change participating teams after the tournament has started.');
        }

        $validator = Validator::make($inputData, [
            'participants' => 'required|array|max:30',
            'participants.*' => 'exists:team,id',
        ]);
        $validData = $validator->validate();

        $desiredArray = $validData['participants'];
        $desired = collect($desiredArray);

        // precondition: ensure all teams are unique
        if ($desired->unique()->count() !== $desired->count()) {
            throw ValidationException::withMessages(['All participating teams must be unique.']);
        }

        DB::beginTransaction();
        $existingParticipantTeams = $tournament->tournamentTeams;
        foreach ($existingParticipantTeams as $participantTeam) {
            $participantTeam->tournamentTeamPlayers()->delete();
            $participantTeam->delete();
        }

        foreach ($desired as $index => $newcomer) {
            $team = Team::findOrFail($newcomer);

            $participantTeam = new TournamentTeam;
            $participantTeam->name = $team->name;
            $participantTeam->seed = $index + 1;
            $participantTeam->fk_team = $team->id;
            $participantTeam->fk_tournament = $tournament->id;
            $participantTeam->save();

            $players = $this->historyService->getPlayersInTeam($team);
            foreach ($players as $player) {
                $ttp = new TournamentTeamPlayer;
                $ttp->fk_player = $player->id;
                $ttp->fk_tournament_team = $participantTeam->id;
                $ttp->save();
            }
        }
        DB::commit();
    }
}
