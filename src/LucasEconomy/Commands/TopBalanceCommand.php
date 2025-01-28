<?php

namespace LucasEconomy\Commands\PlayerCommands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use LucasEconomy\Main;

class TopBalanceCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("top", "Voir le classement des joueurs les plus riches.");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        $topBalances = $this->plugin->getEconomyManager()->getTopBalances();
        $message = "Classement des joueurs les plus riches :\n";
        $position = 1;

        foreach ($topBalances as $playerName => $balance) {
            $message .= "$position. $playerName - $balance\n";
            $position++;
        }

        $sender->sendMessage($message);
        return true;
    }
}