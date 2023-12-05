<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Team;
use App\Models\PlayerTeamHistory;
use App\Values\PlayerTransfer;
use Illuminate\Support\Collection;

class PlayerTeamHistoryService
{
    /** @return Collection<int, PlayerTransfer> */
    public function getTeamTransfersHistory(Team $team): Collection
    {
        $all = collect();
        $teamHistory = $team->getJoinHistory();
        foreach ($teamHistory as $item) {
            $prev = $item->getEarlierByPlayer();
            $all->add(new PlayerTransfer($item->player, $prev?->team, $item->date_since, false));

            $next = $item->getLaterByPlayer();
            if ($next != null) {
                $all->add(new PlayerTransfer($item->player, $next->team, $next->date_since, true));
            }
        }
        return $all;
    }

    /** @return Collection<int, Player> */
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

        PlayerTeamHistory::create([
            'fk_player' => $player->id,
            'date_since' => $today,
            'fk_team' => $teamId,
        ]);
    }

    /** @param array<int> $playerIdArray */
    public function setPlayersInTeam(array $playerIdArray, Team $team): void
    {
        $playerIdCollection = collect($playerIdArray);

        $currentPlayers = $this->getPlayersInTeam($team);
        foreach ($currentPlayers as $player) {
            if (!$playerIdCollection->contains($player->id)) {
                $this->changePlayerTeam($player, null);
            }
        }

        foreach ($playerIdCollection as $playerId) {
            $player = Player::findOrFail($playerId);
            // if ($player == null) continue;
            if (!$currentPlayers->contains($player)) {
                $this->changePlayerTeam($player, $team->id);
            }
        }
    }
}
