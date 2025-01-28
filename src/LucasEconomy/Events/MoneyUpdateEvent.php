<?php

namespace LucasEconomy\Events;

use pocketmine\player\Player;
use pocketmine\event\Event;

class MoneyUpdateEvent extends Event {

    private $player;
    private $newBalance;

    public function __construct(Player $player, float $newBalance) {
        $this->player = $player;
        $this->newBalance = $newBalance;
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getNewBalance(): float {
        return $this->newBalance;
    }
}