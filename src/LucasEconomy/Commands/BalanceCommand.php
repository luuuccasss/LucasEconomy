<?php

namespace LucasEconomy\Commands\PlayerCommands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use LucasEconomy\Main;

class BalanceCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("balance", "Voir votre solde.");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            $balance = $this->plugin->getEconomyManager()->getBalance($sender);
            $sender->sendMessage("Votre solde est de : " . $balance);
            return true;
        }
        $sender->sendMessage("Cette commande doit être utilisée en jeu.");
        return false;
    }
}