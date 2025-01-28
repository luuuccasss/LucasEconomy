<?php

namespace LucasEconomy\Commands\PlayerCommands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use LucasEconomy\Main;

class SeeMoneyCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $description = $this->plugin->getLangMessage("commands.seemoney.description", []);
        parent::__construct("seemoney", $description, "/seemoney <player>");
        $this->setPermission("lucaseconomy.seemoney");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$this->testPermission($sender)) {
            return false;
        }

        if (count($args) < 1) {
            $sender->sendMessage($this->plugin->getLangMessage("usage.seemoney"));
            return false;
        }

        $target = $this->plugin->getServer()->getPlayerByPrefix($args[0]);
        if (!$target instanceof Player) {
            $sender->sendMessage($this->plugin->getLangMessage("errors.player_not_found"));
            return false;
        }

        $balance = $this->plugin->getEconomyManager()->getBalance($target);

        $sender->sendMessage($this->plugin->getLangMessage("success.seemoney", [
            "player" => $target->getName(),
            "balance" => $balance
        ]));

        return true;
    }
}