<?php

namespace LucasEconomy\API;

use pocketmine\player\Player;
use LucasEconomy\Main;

class LucasEconomyAPI {

    private $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function getBalance(Player $player): float {
        return $this->plugin->getEconomyManager()->getBalance($player);
    }

    public function setBalance(Player $player, float $amount): void {
        $this->plugin->getEconomyManager()->setBalance($player, $amount);
    }

    public function addMoney(Player $player, float $amount): void {
        $this->plugin->getEconomyManager()->addMoney($player, $amount);
    }

    public function removeMoney(Player $player, float $amount): void {
        $this->plugin->getEconomyManager()->removeMoney($player, $amount);
    }

    public function transferMoney(Player $from, Player $to, float $amount): bool {
        return $this->plugin->getEconomyManager()->transferMoney($from, $to, $amount);
    }
}