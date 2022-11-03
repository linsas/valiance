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

    public function getLatestPlayerHistory(Player $player)
    {
        return $this->getAllHistoryByPlayer($player)->last();
    }

    public function getAllTeamRelatedHistory(Team $team)
    {
        $all = collect();
        $surface = PlayerTeamHistory::where('fk_player', $team->id);
        foreach ($surface as $item) {
            $all->add($item);
            $next = $item->getLaterByPlayer();
            if ($next != null && $next->fk_team !== $team->id) $all->add($next);
        }
        return $all;
    }

    public function getPlayersInTeamByTeamHistory(Team $team, \Illuminate\Support\Collection $history)
    {
        $historyByPlayer = $history->groupBy('fk_player');
        $players = collect();
        foreach ($historyByPlayer as $playerHistory) {
            $lastPlayerHistory = $playerHistory->last();
            if ($lastPlayerHistory->fk_team === $team->id) {
                $players->add($lastPlayerHistory->player);
            }
        }
        return $players;
    }

    public function getPlayersInTeam(Team $team)
    {
        return $this->getPlayersInTeamByTeamHistory($team, $this->getAllTeamRelatedHistory($team));
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
