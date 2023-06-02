<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidStateException;
use App\Models\Matchup;
use App\Models\Game;
use App\Values\MatchupOutcome;

class MatchupService
{
    public function updateMaps(array $inputData, int $id): void
    {
        $entry = Matchup::findOrFail($id);

        $validator = Validator::make($inputData, [
            'maps' => ['required', 'array', 'exists:map,id'],
        ]);
        $validData = $validator->validate();

        if ($entry->games->contains(fn (Game $game) => $game->isCompleted())) {
            throw new InvalidStateException('Cannot change maps after entering score data.');
        }

        $mapArray = $validData['maps'];
        $mapCollection = collect($mapArray);

        $numGames = $entry->games->count();
        if ($mapCollection->count() !== $numGames) {
            throw new InvalidStateException('Exactly ' . $numGames . ' maps are required.');
        }

        if ($mapCollection->count() !== $mapCollection->unique()->count()) {
            throw new InvalidStateException('All maps must be unique.');
        }

        DB::beginTransaction();
        foreach ($entry->games->sortBy('number') as $game) {
            $game->fk_map = $mapCollection[$game->number - 1];
            $game->save();
        }
        DB::commit();
    }

    public function updateScore(array $inputData, int $matchupId, int $gameNumber): void
    {
        $matchup = Matchup::findOrFail($matchupId);
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

        if (!$game->isCompleted() && $matchup->getOutcome() !== MatchupOutcome::Indeterminate) {
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
            throw new InvalidStateException('There cannot be more than '.$maxGameRounds.' game rounds played');
        }

        if (!($score1 === $victory || $score2 === $victory)) {
            throw new InvalidStateException('One team must have won '.$victory.' game rounds.');
        }

        $game->score1 = $score1;
        $game->score2 = $score2;
        $game->save();
    }
}
