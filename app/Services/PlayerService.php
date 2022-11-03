<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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

        if ($validData['team'] != null) {
            $this->historyService->changePlayerTeam($player, $validData['team']);
        }
    }

    public function findOrFail($id)
    {
        if (!ctype_digit($id)) {
            throw ValidationException::withMessages(['The id is invalid.']);
        }
        $player = Player::findOrFail($id);
        return $player;
    }

    public function update($inputData, $id)
    {
        $player = $this->findOrFail($id);

        $validator = Validator::make($inputData, [
            'alias' => 'required|string|max:255',
            'team' => 'nullable|integer|exists:team,id',
        ]);
        $validData = $validator->validate();

        $player->alias = $validData['alias'];
        $player->save();

        $this->historyService->changePlayerTeam($player, $validData['team']);
    }

    public function destroy($id)
    {
        $player = $this->findOrFail($id);

        $playerHistory = $this->historyService->getAllHistoryByPlayer($player);
        foreach ($playerHistory as $playerHistoryEntry) {
            $playerHistoryEntry->delete();
        }

        $player->delete();
    }
}
