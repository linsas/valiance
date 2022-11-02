<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Team;
use App\Models\PlayerTeamHistory;

class PlayerTeamHistoryService
{
    public function getAllByPlayer(Player $player)
    {
        return PlayerTeamHistory::where('fk_player', $player->id);
    }

    public function getAllByTeam(Team $team)
    {
        $all = collect();
        $surface = PlayerTeamHistory::where('fk_player', $team->id);
        foreach ($surface as $item) {
            $all->add($item);
            $next = $item->getLaterByPlayer();
            if ($next != null) $all->add($next);
        }
        return $all;
    }

    public function getPlayersInTeamByHistory(Team $team, Collection $history)
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
        return $this->getPlayersInTeamByHistory($team, $this->getAllByTeam($team));
    }
}
