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
    public function findOrFailTournament($id)
    {
        if (!ctype_digit($id) && !is_int($id)) {
            throw ValidationException::withMessages(['The id is invalid.']);
        }
        $entry = Tournament::findOrFail($id);
        return $entry;
    }

    public function findOrFailTeam($id)
    {
        if (!ctype_digit($id) && !is_int($id)) {
            throw ValidationException::withMessages(['The id is invalid.']);
        }
        $entry = Team::findOrFail($id);
        return $entry;
    }

    public function updateParticipations($inputData, $tournamentId)
    {
        $tournament = $this->findOrFailTournament($tournamentId);
        if ($tournament->rounds->count() > 0) {
            throw new InvalidStateException('Cannot change participating teams after the tournament has started.');
        }

        $validator = Validator::make($inputData, [
            'participants' => 'array|max:50',
        ]);
        $validData = $validator->validate();
        $desired = collect($validData['participants']);

        // precondition: ensure all teams are unique
        if ($desired->unique()->count() !== $desired->count()) {
            throw ValidationException::withMessages(['All participating teams must be unique.']);
        }

        $preexistingCount = $tournament->tournamentTeams->count();
        $remainingParticipants = $tournament->tournamentTeams->whereIn('id', $desired);

        DB::beginTransaction();
        // step 1: delete removed participants
        $leavingParticipants = $tournament->tournamentTeams->whereNotIn('id', $desired);
        foreach ($leavingParticipants as $participant) {
            $participant->tournamentTeamPlayers->each(function ($item) {
                $item->delete();
            });
            $participant->delete();
        }

        // step 2: change all participants' seeds to non-collision ids (database constraints)
        $maxSeed = max($preexistingCount, $desired->count());
        foreach ($remainingParticipants as $participant) {
            $participant->seed = ++$maxSeed;
            $participant->save();
        }

        // step 3: create new participants
        $remainingTeamIds = $remainingParticipants->map(function ($item) {
            return $item->fk_team;
        });
        foreach ($desired as $newcomer) {
            if ($remainingTeamIds->contains($newcomer)) continue;
            $team = $this->findOrFailTeam($newcomer);

            $participant = new TournamentTeam;
            $participant->name = $team->name;
            $participant->seed = $desired->search($newcomer) + 1;
            $participant->fk_team = $team->id;
            $participant->fk_tournament = $tournament->id;
            $participant->save();
            foreach ($team->players as $player) {
                $ttp = new TournamentTeamPlayer;
                $ttp->fk_player = $player->id;
                $ttp->fk_tournament_team = $participant->id;
                $ttp->save();
            }
        }

        // step 4: set participants' seeds to correct values
        foreach ($remainingParticipants as $participant) {
            dump($participant);
            $participant->seed = $desired->search($participant->fk_team) + 1;
            $participant->save();
        }
        DB::commit();
    }
}