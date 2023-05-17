<?php

namespace App\Values;

use App\Models\Player;
use App\Models\Team;

class PlayerTransfer
{
    private Player $player;
    private ?Team $otherTeam;
    private string $transferDate;
    private bool $isLeaving;

    public function __construct(Player $player, ?Team $otherTeam, string $transferDate, bool $isLeaving)
    {
        $this->player = $player;
        $this->otherTeam = $otherTeam;
        $this->transferDate = $transferDate;
        $this->isLeaving = $isLeaving;
    }

    public function serialize(): array
    {
        return [
            'player' => [
                'id' => $this->player->id,
                'alias' => $this->player->alias,
            ],
            'otherTeam' => $this->otherTeam == null ? null : [
                'id' => $this->otherTeam->id,
                'name' => $this->otherTeam->name,
            ],
            'date' => $this->transferDate,
            'isLeaving' => $this->isLeaving,
        ];
    }
}
