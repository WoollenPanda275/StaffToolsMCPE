<?php

namespace Senpayeh\StaffTools\Tasks;
use pocketmine\scheduler\Task;
use muqsit\invmenu\InvMenu;
use Senpayeh\StaffTools\ToolsGUI;
use Senpayeh\StaffTools\Main;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\item\Item;

class CPS extends Task {

  private $time = 12;
  private $plugin;
  private $sender;
  private $victim;
  private $menu;
  private $main;
  private $num = 0;

  public function __construct(Main $main, ToolsGUI $plugin, Player $sender, Player $victim, InvMenu $menu) {
    $this->plugin = $plugin;
    $this->sender = $sender;
    $this->victim = $victim;
    $this->menu = $menu;
    $this->main = $main;
  }

  public function onRun(int $currentTick) {
    $cps = $this->main->getServer()->getPluginManager()->getPlugin("PreciseCpsCounter");
    $this->time--;
    switch ($this->time) {
        case 10:
          $this->menu->getInventory()->setItem(1, $this->plugin->checkRisk($cps->getCps($this->victim))->setCustomName(C::RESET . $cps->getCps($this->victim) . " CPS"));
          $this->num = $this->num + $cps->getCps($this->victim);
        break;
        case 9:
        $this->menu->getInventory()->setItem(10, $this->plugin->checkRisk($cps->getCps($this->victim))->setCustomName(C::RESET . $cps->getCps($this->victim) . " CPS"));
        $this->num = $this->num + $cps->getCps($this->victim);
        break;
        case 8:
          $this->menu->getInventory()->setItem(19, $this->plugin->checkRisk($cps->getCps($this->victim))->setCustomName(C::RESET . $cps->getCps($this->victim) . " CPS"));
          $this->num = $this->num + $cps->getCps($this->victim);
        break;
        case 7:
          $this->menu->getInventory()->setItem(20, $this->plugin->checkRisk($cps->getCps($this->victim))->setCustomName(C::RESET . $cps->getCps($this->victim) . " CPS"));
          $this->num = $this->num + $cps->getCps($this->victim);
        break;
        case 6:
          $this->menu->getInventory()->setItem(21, $this->plugin->checkRisk($cps->getCps($this->victim))->setCustomName(C::RESET . $cps->getCps($this->victim) . " CPS"));
          $this->num = $this->num + $cps->getCps($this->victim);
        break;
        case 5:
          $this->menu->getInventory()->setItem(12, $this->plugin->checkRisk($cps->getCps($this->victim))->setCustomName(C::RESET . $cps->getCps($this->victim) . " CPS"));
          $this->num = $this->num + $cps->getCps($this->victim);
        break;
        case 4:
          $this->menu->getInventory()->setItem(3, $this->plugin->checkRisk($cps->getCps($this->victim))->setCustomName(C::RESET . $cps->getCps($this->victim) . " CPS"));
          $this->num = $this->num + $cps->getCps($this->victim);
        break;
        case 3:
          $this->menu->getInventory()->setItem(4, $this->plugin->checkRisk($cps->getCps($this->victim))->setCustomName(C::RESET . $cps->getCps($this->victim) . " CPS"));
          $this->num = $this->num + $cps->getCps($this->victim);
        break;
        case 2:
          $this->menu->getInventory()->setItem(5, $this->plugin->checkRisk($cps->getCps($this->victim))->setCustomName(C::RESET . $cps->getCps($this->victim) . " CPS"));
          $this->num = $this->num + $cps->getCps($this->victim);
        break;
        case 1:
          $this->menu->getInventory()->setItem(14, $this->plugin->checkRisk($cps->getCps($this->victim))->setCustomName(C::RESET . $cps->getCps($this->victim) . " CPS"));
          $this->num = $this->num + $cps->getCps($this->victim);
        break;
        case 0:
        $this->menu->getInventory()->setItem(16, Item::get(Item::SKULL, 3, 1)->setCustomName(C::RESET . C::DARK_GRAY . $this->victim->getName() . " CPS Test Results")->setLore([C::RESET . C::WHITE . "Average CPS: " . C::BOLD . $this->num / 10]));
          $this->menu->send($this->sender, C::GREEN . "StaffTools ©");
          $this->main->getScheduler()->cancelTask($this->getTaskId());
        break;
    }
  }

}
