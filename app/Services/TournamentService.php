<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Exceptions\InvalidStateException;
use App\Services\Competition\TournamentFormat;
use App\Models\Tournament;

class TournamentService
{
    public function index()
    {
        return Tournament::all();
    }

    public function store($inputData)
    {
        $validator = Validator::make($inputData, [
            'name' => 'required|string|max:255',
            'format' => ['required', \Illuminate\Validation\Rule::in(TournamentFormat::$validFormats)],
        ]);
        $validData = $validator->validate();

        $entry = new Tournament;
        $entry->name = $validData['name'];
        $entry->format = $validData['format'];
        $entry->save();
    }

    public function findOrFail($id)
    {
        if (!ctype_digit($id) && !is_int($id)) {
            throw ValidationException::withMessages(['The id is invalid.']);
        }
        $entry = Tournament::findOrFail($id);
        return $entry;
    }

    public function update($inputData, $id)
    {
        $entry = $this->findOrFail($id);

        $validator = Validator::make($inputData, [
            'name' => 'required|string|max:255',
            'format' => ['required', \Illuminate\Validation\Rule::in(TournamentFormat::$validFormats)],
        ]);
        $validData = $validator->validate();

        if ($validData['format'] != $entry->format && $entry->rounds->count() > 0) {
            throw new InvalidStateException('Cannot change format after starting tournament.');
        }

        $entry->name = $validData['name'];
        $entry->format = $validData['format'];
        $entry->save();
    }

    public function destroy($id)
    {
        $entry = $this->findOrFail($id);
        if ($entry->rounds->count() > 0) {
            throw new InvalidStateException('Cannot delete tournament after starting.');
        }

        DB::beginTransaction();
        foreach ($entry->tournamentTeams as $participant) {
            $participant->tournamentTeamPlayers()->delete();
        }
        $entry->tournamentTeams()->delete();
        $entry->delete();
        DB::commit();

        $entry->delete();
    }
}
