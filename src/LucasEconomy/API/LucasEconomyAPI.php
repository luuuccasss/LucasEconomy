<?php

namespace LucasEconomy\API;

use pocketmine\player\Player;
use LucasEconomy\Main;

class LucasEconomyAPI {

    /** @var Main $plugin The main instance of the LucasEconomy plugin. */
    private Main $plugin;

    /**
     * Initializes the LucasEconomyAPI.
     *
     * @param Main $plugin The main plugin instance. This is required to access the EconomyManager.
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param Player $player The player whose balance you want to check.
     * @return float The player's current balance.
     */
    public function getBalance(Player $player): float {
        return $this->plugin->getEconomyManager()->getBalance($player);
    }

    /**
     * @param Player $player The player whose balance will be set.
     * @param float $amount The new balance amount. Must be a positive number.
     * @return void
     */
    public function setBalance(Player $player, float $amount): void {
        $this->plugin->getEconomyManager()->setBalance($player, $amount);
    }

    /**
     * @param Player $player The player who will receive the money.
     * @param float $amount The amount of money to add. Must be a positive number.
     * @return void
     */
    public function addMoney(Player $player, float $amount): void {
        $this->plugin->getEconomyManager()->addMoney($player, $amount);
    }

    /**
     * @param Player $player The player from whom the money will be removed.
     * @param float $amount The amount of money to remove. Must be a positive number.
     * @return void
     */
    public function removeMoney(Player $player, float $amount): void {
        $this->plugin->getEconomyManager()->removeMoney($player, $amount);
    }

    /**
     *
     * @param Player $from The player sending the money.
     * @param Player $to The player receiving the money.
     * @param float $amount The amount of money to transfer. Must be a positive number.
     * @return bool Returns true if the transfer was successful, false if the sender doesn't have enough money.
     */
    public function transferMoney(Player $from, Player $to, float $amount): bool {
        return $this->plugin->getEconomyManager()->transferMoney($from, $to, $amount);
    }
}