<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Team;

class TeamService
{
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

        $entry = new Team;
        $entry->name = $validData['name'];
        $entry->save();
    }

    public function findOrFail($id)
    {
        if (!ctype_digit($id) && !is_int($id)) {
            throw ValidationException::withMessages(['The id is invalid.']);
        }
        $entry = Team::findOrFail($id);
        return $entry;
    }

    public function update($inputData, $id)
    {
        $entry = $this->findOrFail($id);

        $validator = Validator::make($inputData, [
            'name' => 'required|string|max:255',
        ]);
        $validData = $validator->validate();

        $entry->name = $validData['name'];
        $entry->save();
    }

    public function destroy($id)
    {
        $entry = $this->findOrFail($id);
        $entry->delete();
    }
}
