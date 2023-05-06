<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Models\Team;

class TeamService
{
    private PlayerTeamHistoryService $historyService;

    public function __construct(PlayerTeamHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function store(array $inputData): void
    {
        $validator = Validator::make($inputData, [
            'name' => 'required|string|max:255',
        ]);
        $validData = $validator->validate();

        $team = new Team;
        $team->name = $validData['name'];
        $team->save();
    }

    public function update(array $inputData, int $id): void
    {
        $team = Team::findOrFail($id);

        $validator = Validator::make($inputData, [
            'name' => 'required|string|max:255',
        ]);
        $validData = $validator->validate();

        $team->name = $validData['name'];
        $team->save();
    }

    public function destroy(int $id): void
    {
        $team = Team::findOrFail($id);

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
