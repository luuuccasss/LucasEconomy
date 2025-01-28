<?php

namespace LucasEconomy\Commands\PlayerCommands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use LucasEconomy\Main;

class PayCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("pay", $plugin->getLangMessage("commands.pay.description"), "/pay <joueur> <montant>");
        $this->setPermission("lucaseconomy.pay");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            if (count($args) < 2) {
                $sender->sendMessage($this->plugin->getLangMessage("usage.pay"));
                return false;
            }

            $target = $this->plugin->getServer()->getPlayerByPrefix($args[0]);
            if (!$target instanceof Player) {
                $sender->sendMessage($this->plugin->getLangMessage("errors.player_not_found"));
                return false;
            }

            $amount = (float) $args[1];
            if ($amount <= 0) {
                $sender->sendMessage($this->plugin->getLangMessage("errors.invalid_amount"));
                return false;
            }

            if ($this->plugin->getEconomyManager()->transferMoney($sender, $target, $amount)) {
                $sender->sendMessage($this->plugin->getLangMessage("success.pay_sender", [
                    "amount" => $amount,
                    "player" => $target->getName()
                ]));
                $target->sendMessage($this->plugin->getLangMessage("success.pay_receiver", [
                    "amount" => $amount,
                    "player" => $sender->getName()
                ]));
            } else {
                $sender->sendMessage($this->plugin->getLangMessage("errors.insufficient_balance"));
            }
            return true;
        }
        $sender->sendMessage($this->plugin->getLangMessage("errors.in_game_only"));
        return false;
    }
}