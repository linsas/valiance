<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Team;

class TeamService
{
    private $historyService;

    public function __construct(PlayerTeamHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function index()
    {
        return Team::all();
    }

    public function store($inputData)
    {
        $validator = Validator::make($inputData, [
            'name' => 'required|string|max:255',
        ]);
        $validData = $validator->validate();

        $team = new Team;
        $team->name = $validData['name'];
        $team->save();
    }

    public function findOrFail($id)
    {
        if (!ctype_digit($id) && !is_int($id)) {
            throw ValidationException::withMessages(['The id is invalid.']);
        }
        $team = Team::findOrFail($id);
        return $team;
    }

    public function update($inputData, $id)
    {
        $team = $this->findOrFail($id);

        $validator = Validator::make($inputData, [
            'name' => 'required|string|max:255',
        ]);
        $validData = $validator->validate();

        $team->name = $validData['name'];
        $team->save();
    }

    public function destroy($id)
    {
        $team = $this->findOrFail($id);

        $teamHistory = $this->historyService->getTeamDirectHistory($team);
        foreach ($teamHistory as $teamHistoryEntry) {
            $teamHistoryEntry->fk_team = null;
            $teamHistoryEntry->save();

            $laterEntry = $this->historyService->getLaterPlayerEntry($teamHistoryEntry);
            if ($laterEntry != null && $laterEntry->fk_team == null) {
                $laterEntry->delete();
            }

            $earlierEntry = $this->historyService->getEarlierPlayerEntry($teamHistoryEntry);
            if ($earlierEntry == null || $earlierEntry->fk_team == null) {
                $teamHistoryEntry->delete();
            }
        }

        $team->delete();
    }

    public function getPlayers(Team $team)
    {
        return $this->historyService->getPlayersInTeam($team);
    }

    public function getRelevantHistory(Team $team)
    {
        return $this->historyService->getTeamRelevantHistory($team);
    }
}
