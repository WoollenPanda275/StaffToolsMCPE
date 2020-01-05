<?php

namespace Senpayeh\StaffTools;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as C;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\Player;
use pocketmine\command\{CommandSender, Command};
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\utils\Config;

class Main extends PluginBase {

  public const PREFIX = C::BOLD . C::DARK_RED . "S" . C::RED . "T " . C::RESET;
  public $menu = [];
  public $logs = [];

  public function onEnable() : void{
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    if (!InvMenuHandler::isRegistered()) {
      InvMenuHandler::register($this);
    }
  }

  public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
    if ($command->getName() == "tools") {
      if ($sender->hasPermission("tools.command")) {
        if (in_array($sender->getName(), $this->menu)) {
          unset($this->menu[array_search($sender->getName(), $this->menu)]);
          foreach ($sender->getInventory()->getContents() as $item) {
            if ($item->getCustomName() == C::RESET . C::DARK_GRAY . "Open GUI " . C::LIGHT_PURPLE . ("(LEFT CLICK)")) {
              $sender->getInventory()->remove($item);
            }
          }
          $sender->sendMessage(self::PREFIX . C::WHITE . "Compass " . C::RED . "removed " . C::WHITE . "from your inventory");
        } else {
          array_push($this->menu, $sender->getName());
          $compass = Item::get(Item::COMPASS, 0, 1)->setCustomName(C::RESET . C::DARK_GRAY . "Open GUI " . C::LIGHT_PURPLE . ("(LEFT CLICK)"));
          $sender->getInventory()->addItem($compass);
          $sender->sendMessage(self::PREFIX . C::WHITE . "Compass " . C::GREEN . "added " . C::WHITE . "to your inventory");
        }
      } else {
        $sender->sendMessage(self::PREFIX . C::RED . "Insufficient permissions");
        return false;
      }
      return true;
    }
    if ($command->getName() == "lol") {
      $cl = new ToolsGUI($this);
      $cl->openGUI($sender, $sender);
      return true;
    }
    return true;
  }

}
