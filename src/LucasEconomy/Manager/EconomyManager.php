<?php

namespace LucasEconomy\Manager;

use pocketmine\player\Player;
use LucasEconomy\Events\MoneyUpdateEvent;
use SQLite3;

class EconomyManager {

    private $balances = [];
    private $db;

    public function __construct() {
        // Initialiser la base de données SQLite
        $this->db = new SQLite3(__DIR__ . "/../../economy.db");
        $this->db->exec("CREATE TABLE IF NOT EXISTS balances (player TEXT PRIMARY KEY, balance REAL)");
    }

    public function getBalance(Player $player): float {
        $playerName = $player->getName();
        if (!isset($this->balances[$playerName])) {
            // Charger le solde depuis la base de données
            $stmt = $this->db->prepare("SELECT balance FROM balances WHERE player = :player");
            $stmt->bindValue(":player", $playerName, SQLITE3_TEXT);
            $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
            $this->balances[$playerName] = $result ? $result["balance"] : 0;
        }
        return $this->balances[$playerName];
    }

    public function setBalance(Player $player, float $amount): void {
        $playerName = $player->getName();
        $this->balances[$playerName] = $amount;

        // Sauvegarder dans la base de données
        $stmt = $this->db->prepare("INSERT OR REPLACE INTO balances (player, balance) VALUES (:player, :balance)");
        $stmt->bindValue(":player", $playerName, SQLITE3_TEXT);
        $stmt->bindValue(":balance", $amount, SQLITE3_FLOAT);
        $stmt->execute();

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
        // Charger les soldes depuis la base de données
        $result = $this->db->query("SELECT player, balance FROM balances ORDER BY balance DESC LIMIT $limit");
        $topBalances = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $topBalances[$row["player"]] = $row["balance"];
        }
        return $topBalances;
    }

    private function callMoneyUpdateEvent(Player $player, float $newBalance): void {
        $event = new MoneyUpdateEvent($player, $newBalance);
        $event->call();
    }

    public function closeDatabase(): void {
        $this->db->close();
    }
}