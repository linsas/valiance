<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Player;

class PlayerService
{
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

        $entry = new Player;
        $entry->alias = $validData['alias'];
        // todo: change to new team history
        $entry->fk_team = $validData['team'] ?? null;
        $entry->save();
    }

    public function findOrFail($id)
    {
        if (!ctype_digit($id)) {
            throw ValidationException::withMessages(['The id is invalid.']);
        }
        $entry = Player::findOrFail($id);
        return $entry;
    }

    public function update($inputData, $id)
    {
        $entry = $this->findOrFail($id);

        $validator = Validator::make($inputData, [
            'alias' => 'required|string|max:255',
            'team' => 'nullable|integer|exists:team,id',
        ]);
        $validData = $validator->validate();

        $entry->alias = $validData['alias'];
        $entry->fk_team = $validData['team']; // ?? null;
        $entry->save();
    }

    public function destroy($id)
    {
        $entry = $this->findOrFail($id);
        $entry->delete();
    }
}
