<?php

namespace LucasEconomy\Commands\AdminCommands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use LucasEconomy\Main;

class AddMoneyCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("addmoney", "Ajouter de l'argent à un joueur.", "/addmoney <joueur> <montant>");
        $this->plugin = $plugin;
        $this->setPermission("lucaseconomy.admin");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$this->testPermission($sender)) {
            return false; // Vérifie la permission de l'utilisateur
        }

        // Vérifie si les arguments sont suffisants
        if (count($args) < 2) {
            $sender->sendMessage($this->plugin->getLangMessage("usage.addmoney"));
            return false;
        }

        // Récupère le joueur cible
        $target = $this->plugin->getServer()->getPlayerByPrefix($args[0]);
        if (!$target instanceof Player) {
            $sender->sendMessage($this->plugin->getLangMessage("errors.player_not_found"));
            return false;
        }

        // Vérifie et parse le montant
        $amount = (float)$args[1];
        if (!is_numeric($args[1]) || $amount <= 0) {
            $sender->sendMessage($this->plugin->getLangMessage("errors.invalid_amount"));
            return false;
        }

        // Ajoute l'argent au joueur cible
        $this->plugin->getEconomyManager()->addMoney($target, $amount);

        // Messages de confirmation
        $sender->sendMessage($this->plugin->getLangMessage("success.addmoney", [
            "amount" => $amount,
            "player" => $target->getName()
        ]));

        $target->sendMessage($this->plugin->getLangMessage("success.addmoney_target", [
            "amount" => $amount
        ]));

        return true;
    }
}
