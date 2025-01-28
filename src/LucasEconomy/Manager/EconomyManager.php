<?php

namespace LucasEconomy\Manager;

use pocketmine\player\Player;
use LucasEconomy\Events\MoneyUpdateEvent;

class EconomyManager {

    private $balances = [];

    public function getBalance(Player $player): float {
        return $this->balances[$player->getName()] ?? 0;
    }

    public function setBalance(Player $player, float $amount): void {
        $this->balances[$player->getName()] = $amount;
        $this->callMoneyUpdateEvent($player, $amount);
    }

    public function addMoney(Player $player, float $amount): void {
        $newBalance = ($this->balances[$player->getName()] ?? 0) + $amount;
        $this->setBalance($player, $newBalance);
    }

    public function removeMoney(Player $player, float $amount): void {
        $newBalance = max(0, ($this->balances[$player->getName()] ?? 0) - $amount);
        $this->setBalance($player, $newBalance);
    }

    public function transferMoney(Player $from, Player $to, float $amount): bool {
        if ($this->getBalance($from) >= $amount) {
            $this->removeMoney($from, $amount);
            $this->addMoney($to, $amount);
            return true;
        }
        return false;
    }

    public function getTopBalances(int $limit = 10): array {
        arsort($this->balances);
        return array_slice($this->balances, 0, $limit, true);
    }

    private function callMoneyUpdateEvent(Player $player, float $newBalance): void {
        $event = new MoneyUpdateEvent($player, $newBalance);
        $event->call();
    }
}