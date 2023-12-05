<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidStateException;
use App\Models\Player;

class PlayerService
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
            'alias' => 'required|string|max:255',
            'team' => 'nullable|integer|exists:team,id',
        ]);
        $validData = $validator->validate();

        $player = Player::create([
            'alias' => $validData['alias'],
        ]);

        $this->historyService->changePlayerTeam($player, $validData['team'] ?? null);
    }

    /** @param array<string, mixed> $inputData */
    public function update(array $inputData, int $id): void
    {
        $player = Player::findOrFail($id);

        $validator = Validator::make($inputData, [
            'alias' => 'required|string|max:255',
            'team' => 'nullable|integer|exists:team,id',
        ]);
        $validData = $validator->validate();

        $player->alias = $validData['alias'];
        $player->save();

        $this->historyService->changePlayerTeam($player, $validData['team'] ?? null);
    }

    public function destroy(int $id): void
    {
        $player = Player::findOrFail($id);

        if ($player->tournamentTeamPlayers->count() > 0) throw new InvalidStateException('Cannot delete a player with participations.');

        DB::beginTransaction();
        $player->history()->delete();
        $player->delete();
        DB::commit();
    }
}
