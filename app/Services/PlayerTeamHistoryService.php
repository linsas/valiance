<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Team;
use App\Models\PlayerTeamHistory;
use App\Values\PlayerTransfer;
use Illuminate\Support\Collection;

class PlayerTeamHistoryService
{
    public function getTeamTransfersHistory(Team $team): Collection
    {
        $all = collect();
        $teamHistory = $team->getJoinHistory();
        foreach ($teamHistory as $item) {
            $prev = $item->getEarlierByPlayer();
            if ($prev == null) {
                $all->add(new PlayerTransfer($item->player, null, $item->date_since, false)); // special case for first entry for player
            } else if ($prev->fk_team !== $team->id) {
                $all->add(new PlayerTransfer($item->player, $prev->team, $item->date_since, false));
            }

            $next = $item->getLaterByPlayer();
            if ($next != null && $next->fk_team !== $team->id) {
                $all->add(new PlayerTransfer($item->player, $next->team, $next->date_since, true));
            }
        }
        return $all;
    }

    public function getPlayersInTeam(Team $team): Collection
    {
        $players = collect();
        $uniqueJoiners = $team->getJoinHistory()->unique('fk_player');
        foreach ($uniqueJoiners as $item) {
            $playerLatest = $item->player->getLatestHistory();

            if ($playerLatest == null) continue;
            if ($playerLatest->fk_team === $team->id) $players->add($item->player);
        }
        return $players;
    }

    public function changePlayerTeam(Player $player, ?int $teamId = null): void
    {
        $latestPlayerHistory = $player->getLatestHistory();

        if ($latestPlayerHistory == null && $teamId == null) return;

        $today = date('Y-m-d');
        if ($latestPlayerHistory != null && $latestPlayerHistory->date_since === $today) {
            $latestPlayerHistory->delete();
            $latestPlayerHistory = $player->getLatestHistory();
        }

        if ($latestPlayerHistory != null && $latestPlayerHistory->fk_team === $teamId) return;

        $newHistory = new PlayerTeamHistory;
        $newHistory->fk_player = $player->id;
        $newHistory->date_since = $today;
        $newHistory->fk_team = $teamId;
        $newHistory->save();
    }
}
