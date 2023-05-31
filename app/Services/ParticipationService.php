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

            $participantTeam = TournamentTeam::create([
                'name' => $team->name,
                'seed' => $index + 1,
                'fk_team' => $team->id,
                'fk_tournament' => $tournament->id,
            ]);

            $players = $this->historyService->getPlayersInTeam($team);
            foreach ($players as $player) {
                TournamentTeamPlayer::create([
                    'fk_player' => $player->id,
                    'fk_tournament_team' => $participantTeam->id,
                ]);
            }
        }
        DB::commit();
    }
}
