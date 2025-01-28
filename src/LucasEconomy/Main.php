<?php

namespace LucasEconomy;

use pocketmine\plugin\PluginBase;
use LucasEconomy\Manager\EconomyManager;
use LucasEconomy\Commands\PlayerCommands\BalanceCommand;
use LucasEconomy\Commands\PlayerCommands\PayCommand;
use LucasEconomy\Commands\PlayerCommands\TopBalanceCommand;
use LucasEconomy\Commands\AdminCommands\AddMoneyCommand;
use LucasEconomy\Commands\AdminCommands\RemoveMoneyCommand;
use LucasEconomy\Commands\AdminCommands\SetMoneyCommand;

class Main extends PluginBase {

    private static $instance;
    private $economyManager;

    public function onEnable(): void {
        self::$instance = $this;

        // Charger la configuration
        $this->saveDefaultConfig();
        $this->reloadConfig();

        // Initialiser l'EconomyManager
        $this->economyManager = new EconomyManager($this);

        // Enregistrer les commandes joueur
        $this->getServer()->getCommandMap()->register("LucasEconomy", new BalanceCommand($this));
        $this->getServer()->getCommandMap()->register("LucasEconomy", new PayCommand($this));
        $this->getServer()->getCommandMap()->register("LucasEconomy", new TopBalanceCommand($this));

        // Enregistrer les commandes admin
        $this->getServer()->getCommandMap()->register("LucasEconomy", new AddMoneyCommand($this));
        $this->getServer()->getCommandMap()->register("LucasEconomy", new RemoveMoneyCommand($this));
        $this->getServer()->getCommandMap()->register("LucasEconomy", new SetMoneyCommand($this));

        $this->getLogger()->info("LucasEconomy activé !");
    }

    public function onDisable(): void {
        $this->economyManager->closeDatabase();
        $this->getLogger()->info("LucasEconomy désactivé !");
    }

    public static function getInstance(): self {
        return self::$instance;
    }

    public function getEconomyManager(): EconomyManager {
        return $this->economyManager;
    }
}