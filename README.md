# LucasEconomy Plugin Documentation



LucasEconomy is a plugin that allows you to manage player economies on your server. It’s simple to use, provides admin and player commands, and includes a developer-friendly API for custom features.

---

## Features
- Manage player balances: add, remove, set, or transfer money.
- Developer API for easy integration with other plugins.

---

## API Overview

### **LucasEconomyAPI**

| Method                               | Description                            |
|--------------------------------------|----------------------------------------|
| `getBalance(Player $player): float`  | Get a player's balance.               |
| `setBalance(Player $player, float $amount): void` | Set a player's balance.  |
| `addMoney(Player $player, float $amount): void`   | Add money to a player.   |
| `removeMoney(Player $player, float $amount): void`| Remove money from a player.|
| `transferMoney(Player $from, Player $to, float $amount): bool` | Transfer money between players. |

---

## Installation
1. Download the plugin and place it in the `plugins` folder.
2. Start the server to generate the configuration files.
3. Use the commands and API as needed.

## [DISCORD](https://discord.gg/33PskyeFh3)

---
