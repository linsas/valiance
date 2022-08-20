<?php

namespace App\Services\Competition;

use Illuminate\Validation\ValidationException;
use App\Models\Matchup;

class MatchupService
{
    public function index()
    {
        return Matchup::all();
    }

    public function findOrFail($id)
    {
        if (!ctype_digit($id)) {
            throw ValidationException::withMessages(['The id is invalid.']);
        }
        $entry = Matchup::findOrFail($id);
        return $entry;
    }
}
