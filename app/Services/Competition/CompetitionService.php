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
                throw new InvalidStateException('There must be exactly ' . $format->getTeamsNeeded() . ' participating teams in the tournament.');
            }
        }

        $roundIndex = $lastRound == null ? 0 : $lastRound->number;
        $roundRuleset = $rules[$roundIndex];

        foreach ($roundRuleset as $pairing) {
            $pairing->fill($tournament);
        }

        DB::beginTransaction();
        $round = Round::create([
            'fk_tournament' => $tournament->id,
            'number' => $roundIndex + 1,
        ]);

        foreach ($roundRuleset as $pairing) {
            $this->matchPair($pairing, $round);
        }
        DB::commit();
    }

    public function matchPair(ProgressionRule $matchRule, Round $round): void
    {
        $high = $matchRule->takeHigh();
        $low = $matchRule->takeLow();

        $match = Matchup::create([
            'fk_round' => $round->id,
            'fk_team1' => $high->id,
            'fk_team2' => $low->id,
            'significance' => $matchRule->matchupSignificance,
        ]);

        for ($j = 0; $j < $matchRule->numGames; $j++) {
            Game::create([
                'fk_matchup' => $match->id,
                'number' => $j + 1,
            ]);
        }
    }
}
