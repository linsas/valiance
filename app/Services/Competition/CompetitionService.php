<?php

namespace App\Services\Competition;


use Illuminate\Support\Facades\DB;
use App\Exceptions\InvalidStateException;
use App\Models\Round;
use App\Models\Matchup;
use App\Models\Game;
use App\Models\Tournament;
use App\Services\Competition\ProgressionRule;
use App\Values\MatchupOutcome;

class CompetitionService
{
    public function advance(int $tournamentId): void
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $format = $tournament->getFormat();
        $rules = $format->getRules();

        if ($tournament->rounds->count() >= count($rules)) {
            throw new InvalidStateException('Cannot advance the tournament past its final round.');
        }

        $lastRound = $tournament->rounds->sortBy('number')->last();
        if ($lastRound != null) {
            if ($lastRound->matchups->contains(fn ($matchup) => $matchup->getOutcome() === MatchupOutcome::Indeterminate)) {
                throw new InvalidStateException('All matchups must be completed before starting the next round.');
            }
        } else {
            if ($tournament->tournamentTeams->count() !== $format->getTeamsNeeded()) {
                throw new InvalidStateException('There must be exactly (' . $format->getTeamsNeeded() . ') participating teams in the tournament.');
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

    public function matchPair(ProgressionRule $matchRule, Round $round): void
    {
        $high = $matchRule->takeHigh();
        $low = $matchRule->takeLow();

        $match = new Matchup;
        $match->fk_round = $round->id;
        $match->fk_team1 = $high->id;
        $match->fk_team2 = $low->id;
        $match->significance = $matchRule->matchupSignificance;
        $match->save();

        for ($j = 0; $j < $matchRule->numGames; $j++) {
            $game = new Game;
            $game->fk_matchup = $match->id;
            $game->number = $j + 1;
            $game->save();
        }
    }
}
