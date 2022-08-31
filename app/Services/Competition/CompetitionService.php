<?php

namespace App\Services\Competition;


use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Exceptions\InvalidStateException;
use App\Services\TournamentService;
use App\Models\Round;
use App\Models\Matchup;
use App\Models\Game;

class CompetitionService
{
    private $tournamentService;

    public function __construct(TournamentService $tournamentService) {
        $this->tournamentService = $tournamentService;
    }

    public function advance($tournamentId)
    {
        if (!ctype_digit($tournamentId)) {
            throw ValidationException::withMessages(['The id is invalid.']);
        }

        $tournament = $this->tournamentService->findOrFail($tournamentId);
        $format = TournamentFormat::getFormat($tournament->format);
        $rules = $format->getRules();

        if ($tournament->rounds->count() >= count($rules)) {
            throw new InvalidStateException('Cannot advance the tournament past its final round.');
        }

        $lastRound = $tournament->rounds->sortBy('number')->last();
        if ($lastRound != null) {
            if ($lastRound->matchups->contains(function ($matchup) {
                return $matchup->getOutcome() === 0;
            })) {
                throw new InvalidStateException('All matchups must be completed before starting the next round.');
            }
        } else {
            if ($tournament->tournamentTeams->count() !== $format->teamsNeeded) {
                throw new InvalidStateException('There must be exactly (' . $format->teamsNeeded . ') participating teams in the tournament.');
            }
        }

        $roundIndex = $lastRound == null ? 0 : $lastRound->number;
        $roundRuleset = $rules[$roundIndex];

        foreach ($roundRuleset as $pairing) {
            $pairing->fill($tournament);
        }

        DB::beginTransaction();
        $round = new Round;
        $round->fk_tournament = $tournament->id;
        $round->number = $roundIndex + 1;
        $round->save();

        foreach ($roundRuleset as $pairing) {
            $this->matchPair($pairing, $round);
        }
        DB::commit();
    }

    public function matchPair(ProgressionRule $matchRule, Round $round)
    {
        $high = $matchRule->takeHigh();
        $low = $matchRule->takeLow();

        $match = new Matchup;
        $match->fk_round = $round->id;
        $match->fk_team1 = $high->id;
        $match->fk_team2 = $low->id;
        $match->key = $matchRule->matchupKey;
        $match->save();

        for ($j = 0; $j < $matchRule->numGames; $j++) {
            $game = new Game;
            $game->fk_matchup = $match->id;
            $game->number = $j + 1;
            $game->save();
        }
    }
}
