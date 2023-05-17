<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Team;
use App\Models\PlayerTeamHistory;
use App\Values\PlayerTransfer;
use Illuminate\Support\Collection;

class PlayerTeamHistoryService
{
    public function getEarlierPlayerEntry(PlayerTeamHistory $entry): ?PlayerTeamHistory
    {
        return PlayerTeamHistory::where('fk_player', $entry->fk_player)->where('date_since', '<', $entry->date_since)->orderByDesc('date_since')->first();
    }

    public function getLaterPlayerEntry(PlayerTeamHistory $entry): ?PlayerTeamHistory
    {
        return PlayerTeamHistory::where('fk_player', $entry->fk_player)->where('date_since', '>', $entry->date_since)->orderBy('date_since')->first();
    }

    public function getLatestPlayerHistory(Player $player): ?PlayerTeamHistory
    {
        return PlayerTeamHistory::where('fk_player', $player->id)->orderByDesc('date_since')->first();
    }

    public function getTeamJoinHistory(Team $team): Collection
    {
        return PlayerTeamHistory::with('player')->where('fk_team', $team->id)->get();
    }

    public function getTeamTransfersHistory(Team $team): Collection
    {
        $all = collect();
        $teamHistory = $this->getTeamJoinHistory($team);
        foreach ($teamHistory as $item) {
            $prev = $this->getEarlierPlayerEntry($item);
            if ($prev == null) {
                $all->add(new PlayerTransfer($item->player, null, $item->date_since, false)); // special case for first entry for player
            } else if ($prev->fk_team !== $team->id) {
                $all->add(new PlayerTransfer($item->player, $prev->team, $item->date_since, false));
            }

            $next = $this->getLaterPlayerEntry($item);
            if ($next != null && $next->fk_team !== $team->id) {
                $all->add(new PlayerTransfer($item->player, $next->team, $next->date_since, true));
            }
        }
        return $all;
    }

    public function getPlayersInTeam(Team $team): Collection
    {
        $players = collect();
        $surface = $this->getTeamJoinHistory($team)->unique('fk_player'); // every time a player joined a team (once per player)
        foreach ($surface as $item) {
            $playerLatest = $this->getLatestPlayerHistory($item->player);

            if ($playerLatest == null) continue;
            if ($playerLatest->fk_team === $team->id) $players->add($item->player);
        }
        return $players;
    }

    public function changePlayerTeam(Player $player, ?int $teamId = null): void
    {
        $latestPlayerHistory = $this->getLatestPlayerHistory($player);

        if ($latestPlayerHistory == null && $teamId == null) return;

        $today = date('Y-m-d');
        if ($latestPlayerHistory != null && $latestPlayerHistory->date_since === $today) {
            $latestPlayerHistory->delete();
            $latestPlayerHistory = $this->getLatestPlayerHistory($player);
        }

        if ($latestPlayerHistory != null && $latestPlayerHistory->fk_team === $teamId) return;

        $newHistory = new PlayerTeamHistory;
        $newHistory->fk_player = $player->id;
        $newHistory->date_since = $today;
        $newHistory->fk_team = $teamId;
        $newHistory->save();
    }
}
