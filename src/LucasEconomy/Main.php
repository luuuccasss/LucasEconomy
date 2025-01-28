<?php

namespace LucasEconomy;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
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
    private $lang;

    public function onEnable(): void {
        self::$instance = $this;

        $this->saveDefaultConfig();
        $this->saveResource("lang.yml");
        $this->reloadConfig();

        $this->lang = new Config($this->getDataFolder() . "lang.yml", Config::YAML);

        $this->economyManager = new EconomyManager($this->getDataFolder() . "players.json");

        $this->getServer()->getCommandMap()->register("LucasEconomy", new BalanceCommand($this));
        $this->getServer()->getCommandMap()->register("LucasEconomy", new PayCommand($this));
        $this->getServer()->getCommandMap()->register("LucasEconomy", new TopBalanceCommand($this));

        $this->getServer()->getCommandMap()->register("LucasEconomy", new AddMoneyCommand($this));
        $this->getServer()->getCommandMap()->register("LucasEconomy", new RemoveMoneyCommand($this));
        $this->getServer()->getCommandMap()->register("LucasEconomy", new SetMoneyCommand($this));

        $this->getLogger()->info("LucasEconomy activé !");
    }

    public function onDisable(): void {
        $this->getLogger()->info("LucasEconomy désactivé !");
    }

    public static function getInstance(): self {
        return self::$instance;
    }

    public function getEconomyManager(): EconomyManager {
        return $this->economyManager;
    }

    public function getLangMessage(string $key, array $replace = []): string {
        $message = $this->lang->getNested("messages." . $key, "Message introuvable pour la clé : " . $key);
        foreach ($replace as $search => $value) {
            $message = str_replace("{" . $search . "}", $value, $message);
        }
        return $message;
    }
}
