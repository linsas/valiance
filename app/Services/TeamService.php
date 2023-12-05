<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use App\Exceptions\InvalidStateException;
use App\Models\Team;
use App\Models\Player;
use App\Values\PlayerTransfer;

class TeamService
{
    private PlayerTeamHistoryService $historyService;

    public function __construct(PlayerTeamHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /** @param array<string, mixed> $inputData */
    public function store(array $inputData): void
    {
        $validator = Validator::make($inputData, [
            'name' => 'required|string|max:255',
        ]);
        $validData = $validator->validate();

        Team::create([
            'name' => $validData['name'],
        ]);
    }

    /** @param array<string, mixed> $inputData */
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

        if ($team->tournamentTeams->count() > 0) throw new InvalidStateException('Cannot delete a team with participations.');

        DB::beginTransaction();
        $teamHistory = $team->getJoinHistory();
        foreach ($teamHistory as $teamHistoryEntry) {
            $teamHistoryEntry->fk_team = null;
            $teamHistoryEntry->save();

            $laterEntry = $teamHistoryEntry->getLaterByPlayer();
            if ($laterEntry != null && $laterEntry->fk_team == null) {
                $laterEntry->delete();
            }

            $earlierEntry = $teamHistoryEntry->getEarlierByPlayer();
            if ($earlierEntry?->fk_team == null) {
                $teamHistoryEntry->delete();
            }
        }

        $team->delete();
        DB::commit();
    }

    /** @param array<string, mixed> $inputData */
    public function setPlayers(array $inputData, int $id): void
    {
        $team = Team::findOrFail($id);

        $validator = Validator::make($inputData, [
            'players' => 'required|array|max:30',
            'players.*' => 'exists:player,id',
        ]);
        $validData = $validator->validate();

        $playerArray = $validData['players'];

        $this->historyService->setPlayersInTeam($playerArray, $team);
    }

    /** @return Collection<int, Player> */
    public function getPlayers(Team $team): Collection
    {
        return $this->historyService->getPlayersInTeam($team);
    }

    /** @return Collection<int, PlayerTransfer> */
    public function getTransfersHistory(Team $team): Collection
    {
        return $this->historyService->getTeamTransfersHistory($team);
    }
}
