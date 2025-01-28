<?php

namespace LucasEconomy\Commands\AdminCommands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use LucasEconomy\Main;

class RemoveMoneyCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("removemoney", "Retirer de l'argent à un joueur.", "/removemoney <joueur> <montant>");
        $this->plugin = $plugin;
        $this->setPermission("lucaseconomy.admin");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (count($args) < 2) {
            $sender->sendMessage("Usage: /removemoney <joueur> <montant>");
            return false;
        }

        $target = $this->plugin->getServer()->getPlayerByPrefix($args[0]);
        if (!$target instanceof Player) {
            $sender->sendMessage("Joueur introuvable.");
            return false;
        }

        $amount = (float) $args[1];
        if ($amount <= 0) {
            $sender->sendMessage("Montant invalide.");
            return false;
        }

        $this->plugin->getEconomyManager()->removeMoney($target, $amount);
        $sender->sendMessage("Vous avez retiré $amount à " . $target->getName());
        $target->sendMessage("Vous avez perdu $amount.");
        return true;
    }
}