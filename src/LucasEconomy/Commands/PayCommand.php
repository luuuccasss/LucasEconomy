<?php

namespace LucasEconomy\Commands\PlayerCommands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use LucasEconomy\Main;

class PayCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("pay", "Envoyer de l'argent à un joueur.", "/pay <joueur> <montant>");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            if (count($args) < 2) {
                $sender->sendMessage("Usage: /pay <joueur> <montant>");
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

            if ($this->plugin->getEconomyManager()->transferMoney($sender, $target, $amount)) {
                $sender->sendMessage("Vous avez envoyé $amount à " . $target->getName());
                $target->sendMessage("Vous avez reçu $amount de " . $sender->getName());
            } else {
                $sender->sendMessage("Solde insuffisant.");
            }
            return true;
        }
        $sender->sendMessage("Cette commande doit être utilisée en jeu.");
        return false;
    }
}