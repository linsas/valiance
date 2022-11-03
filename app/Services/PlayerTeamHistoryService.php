<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Team;
use App\Models\PlayerTeamHistory;

class PlayerTeamHistoryService
{
    public function getAllHistoryByPlayer(Player $player)
    {
        return PlayerTeamHistory::where('fk_player', $player->id)->orderBy('date_since')->get();
    }

    public function getEarlierPlayerEntry(PlayerTeamHistory $entry)
    {
        return PlayerTeamHistory::where('fk_player', $entry->fk_player)->where('date_since', '<', $entry->date_since)->orderBy('date_since')->get()->last();
    }

    public function getLaterPlayerEntry(PlayerTeamHistory $entry)
    {
        return PlayerTeamHistory::where('fk_player', $entry->fk_player)->where('date_since', '>', $entry->date_since)->orderBy('date_since')->get()->first();
    }

    public function getLatestPlayerHistory(Player $player)
    {
        return $this->getAllHistoryByPlayer($player)->last();
    }

    public function getTeamRelevantHistory(Team $team)
    {
        $all = collect();
        $surface = PlayerTeamHistory::where('fk_team', $team->id)->get();
        foreach ($surface as $item) {
            $all->add($item);
            $next = $this->getLaterPlayerEntry($item);
            if ($next != null && $next->fk_team !== $team->id) $all->add($next);
        }
        return $all;
    }

    public function getPlayersInTeam(Team $team)
    {
        $players = collect();
        $surface = PlayerTeamHistory::where('fk_team', $team->id)->get()->unique('fk_player'); // every time a player joined a team (once per player)
        foreach ($surface as $item) {
            $playerLatest = $this->getLatestPlayerHistory($item->player);
            if ($playerLatest->fk_team === $team->id) $players->add($item->player);
        }
        return $players;
    }

    public function changePlayerTeam(Player $player, ?int $teamId = null)
    {
        $latestPlayerHistory = $this->getLatestPlayerHistory($player);

        $today = date('Y-m-d');
        if ($latestPlayerHistory != null) {
            if ($latestPlayerHistory->date_since === $today) {
                $latestPlayerHistory->delete();
                $latestPlayerHistory = $this->getLatestPlayerHistory($player);
            }
            if ($latestPlayerHistory->fk_team === $teamId) return;
        }

        $newHistory = new PlayerTeamHistory;
        $newHistory->fk_player = $player->id;
        $newHistory->date_since = $today;
        $newHistory->fk_team = $teamId;
        $newHistory->save();
    }
}
