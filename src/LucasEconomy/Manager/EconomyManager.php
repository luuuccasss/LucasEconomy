<?php

namespace LucasEconomy\Manager;

use pocketmine\player\Player;
use LucasEconomy\Events\MoneyUpdateEvent;

class EconomyManager {

    private $balances = [];
    private $dataPath;

    public function __construct(string $dataPath) {
        $this->dataPath = $dataPath;
        $this->loadPlayerData();
    }

    private function loadPlayerData(): void {
        if (!file_exists($this->dataPath)) {
            file_put_contents($this->dataPath, json_encode([]));
        }
        $this->balances = json_decode(file_get_contents($this->dataPath), true);
    }

    private function savePlayerData(): void {
        file_put_contents($this->dataPath, json_encode($this->balances));
    }

    public function getBalance(Player $player): float {
        $playerName = $player->getName();
        return $this->balances[$playerName] ?? 0;
    }

    public function setBalance(Player $player, float $amount): void {
        $playerName = $player->getName();
        $this->balances[$playerName] = $amount;
        $this->savePlayerData();
        $this->callMoneyUpdateEvent($player, $amount);
    }

    public function addMoney(Player $player, float $amount): void {
        $newBalance = $this->getBalance($player) + $amount;
        $this->setBalance($player, $newBalance);
    }

    public function removeMoney(Player $player, float $amount): void {
        $newBalance = max(0, $this->getBalance($player) - $amount);
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