<?php

namespace App\Services\Competition;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Exceptions\InvalidStateException;
use App\Models\Matchup;
use App\Models\Game;

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

    public function updateMaps($inputData, $id)
    {
        $entry = $this->findOrFail($id);

        $validator = Validator::make($inputData, [
            'maps' => ['required', 'array', \Illuminate\Validation\Rule::in(Game::$validMaps)],
        ]);
        $validData = $validator->validate();

        if (!$entry->games->every(function ($game) {
            return !$game->isCompleted();
        })) {
            throw new InvalidStateException('Cannot change maps after entering score data.');
        }

        $mapCollection = collect($validData['maps']);
        $numGames = $entry->games->count();
        if ($mapCollection->count() !== $numGames) {
            throw new InvalidStateException('Exactly ' . $numGames . ' maps are required.');
        }

        if ($mapCollection->count() !== $mapCollection->unique()->count()) {
            throw new InvalidStateException('All maps must be unique.');
        }

        DB::beginTransaction();
        foreach ($entry->games->sortBy('number') as $game) {
            $game->map = $mapCollection[$game->number - 1];
            $game->save();
        }
        DB::commit();
    }

    public function updateScore($inputData, $matchupId, $gameNumber)
    {
        if (!ctype_digit($gameNumber)) {
            throw ValidationException::withMessages(['The id is invalid.']);
        }

        $matchup = $this->findOrFail($matchupId);
        if ($matchup->round->number !== $matchup->round->tournament->rounds->max('number')) {
            throw new InvalidStateException('Cannot change score after the round is completed.');
        }

        $game = Game::where('fk_matchup', $matchup->id)->where('number', $gameNumber)->firstOrFail();

        if ($game->map == null) {
            throw new InvalidStateException('Cannot change score before entering map info.');
        }

        $previous = $matchup->games->where('number', $gameNumber - 1)->first();
        if ($previous != null && !$previous->isCompleted()) {
            throw new InvalidStateException('Cannot change score before entering previous game info.');
        }

        $next = $matchup->games->where('number', $gameNumber + 1)->first();
        if ($next != null && $next->isCompleted()) {
            throw new InvalidStateException('Cannot change score after entering next game info.');
        }

        if (!$game->isCompleted() && $matchup->getOutcome() !== 0) {
            throw new InvalidStateException('Cannot change score after a decisive matchup outcome.');
        }

        $victory = Game::$roundsPerHalf + 1;
        $maxGameRounds = Game::$roundsPerHalf * 2;

        $validator = Validator::make($inputData, [
            'score1' => 'required|int|min:0|max:' . $victory,
            'score2' => 'required|int|min:0|max:' . $victory,
        ]);
        $validData = $validator->validate();

        $score1 = intval($validData['score1']);
        $score2 = intval($validData['score2']);

        if ($score1 + $score2 > $maxGameRounds) {
            throw new InvalidStateException('Invalid score.'); // impossible
        }

        if (!($score1 === $victory || $score2 === $victory)) {
            throw new InvalidStateException('Invalid score.'); // one team must be a clear winner
        }

        $game->score1 = $score1;
        $game->score2 = $score2;
        $game->save();
    }
}
