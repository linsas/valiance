<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Models\Player;

class PlayerService
{
    private $historyService;

    public function __construct(PlayerTeamHistoryService $historyService) {
        $this->historyService = $historyService;
    }

    public function index()
    {
        return Player::all();
    }

    public function store($inputData)
    {
        $validator = Validator::make($inputData, [
            'alias' => 'required|string|max:255',
            'team' => 'nullable|integer|exists:team,id',
        ]);
        $validData = $validator->validate();

        $player = new Player;
        $player->alias = $validData['alias'];
        $player->save();

        $this->historyService->changePlayerTeam($player, $validData['team'] ?? null);
    }

    public function update($inputData, $id)
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

    public function destroy($id)
    {
        $player = Player::findOrFail($id);

        $playerHistory = $this->historyService->getAllHistoryByPlayer($player);
        foreach ($playerHistory as $playerHistoryEntry) {
            $playerHistoryEntry->delete();
        }

        $player->delete();
    }
}
