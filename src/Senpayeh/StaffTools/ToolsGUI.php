<?php

namespace Senpayeh\StaffTools;
use muqsit\invmenu\InvMenu;
use pocketmine\Player;
use pocketmine\item\{Item, ItemBlock};
use pocketmine\block\Block;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\utils\TextFormat as C;
use Senpayeh\StaffTools\Tasks\CPS;
use Senpayeh\StaffTools\Logs\Files;
use pocketmine\utils\Config;

class ToolsGUI {

  private $plugin;

  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }

  public function checkRisk(int $cps) : ItemBlock{
    if ($cps <= 9) {
      return Item::get(Block::WOOL, 5, 1);
    }
    if ($cps >= 10 and $cps <= 14) {
      return Item::get(Block::WOOL, 4, 1);
    }
    if ($cps >= 15) {
      return Item::get(Block::WOOL, 14, 1);
    }
  }

  public function openGUI(Player $sender, Player $victim) {
    $alias = $this->plugin->getServer()->getPluginManager()->getPlugin("Alias");
    $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
    $warn = new Files($this->plugin);
    $file = new Config($alias->getDataFolder() . "players/" . $victim->getAddress() . ".txt");
    $names = $file->getAll(true);
    $names = implode(" \n", $names);
    $menu->getInventory()->setContents([
      4 => Item::get(Item::NETHER_STAR, 0, 1)->setCustomName(C::RESET . C::GOLD . "Warnings: " . C::BOLD . $warn->getLines($victim)),
      11 => Item::get(Item::CLOCK, 0, 1)->setCustomName(C::RESET . C::BLUE . "CPS Test"),
      13 => Item::get(Item::BOOK, 0, 1)->setCustomName(C::RESET . C::DARK_GREEN . "Toggle Logs"),
      15 => Item::get(Block::REDSTONE_TORCH, 0, 1)->setCustomName(C::RESET . C::BLUE . "Aliases")->setLore([C::AQUA . $names]),
      37 => Item::get(Item::SLIMEBALL, 0, 1)->setCustomName(C::RESET . C::GREEN . "Ping:")->setLore([C::DARK_GREEN . $victim->getPing() . "ms"]),
      40 => Item::get(Item::SKULL, 3, 1)->setCustomName(C::RESET . C::DARK_GRAY . $victim->getName()),
      43 => Item::get(Block::COAL_BLOCK, 0, 1)->setCustomName(C::RESET . C::RED . "Leave Menu"),
    ]);

    if (!in_array($victim->getName(), $this->plugin->logs)) {
      $menu->getInventory()->setItem(22, Item::get(Block::WOOL, 14, 1)->setCustomName(C::RESET . C::RED . "OFF"));
    } else {
      $menu->getInventory()->setItem(22, Item::get(Block::WOOL, 13, 1)->setCustomName(C::RESET . C::DARK_GREEN . "ON"));
    }
    $menu->send($sender, C::GREEN . "StaffTools ©");
    $menu->setListener(function (Player $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) use ($victim, $menu) : bool{
      switch ($itemClicked->getId()) {
        case Item::CLOCK:
          if ($this->plugin->getServer()->getPluginManager()->getPlugin("PreciseCpsCounter") == null) {
            $player->sendMessage("You need PreciseCpsCounter by luca28pet to use this feature");
            $player->removeWindow($action->getInventory());
            return false;
          }
          $player->removeWindow($action->getInventory());
          $menu1 = InvMenu::create(InvMenu::TYPE_CHEST);
          $menu1->readonly();
          $this->plugin->getScheduler()->scheduleRepeatingTask(new CPS($this->plugin, $this, $player, $victim, $menu1), 22);
          return false;
          break;
        case Item::BOOK:
          if (in_array($victim->getName(), $this->plugin->logs)) {
            $menu->getInventory()->setItem(22, Item::get(Block::WOOL, 14, 1)->setCustomName(C::RESET . C::RED . "OFF"));
            unset($this->plugin->logs[array_search($victim->getName(), $this->plugin->logs)]);
          } else {
            $menu->getInventory()->setItem(22, Item::get(Block::WOOL, 13, 1)->setCustomName(C::RESET . C::DARK_GREEN . "ON"));
            array_push($this->plugin->logs, $victim->getName());
          }
          return false;
          break;
        case Item::COAL_BLOCK:
          $player->removeWindow($action->getInventory());
          return false;
          break;
        default:
        return false;
      }
    });
  }

}
