<?php

namespace senpayeh\stafftools\tasks;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use senpayeh\stafftools\StaffTools;
use pocketmine\utils\TextFormat as C;
use muqsit\invmenu\InvMenu;

class CPS extends Task {

    /** @var StaffTools */
    private $plugin;
    /** @var InvMenu */
    private $menu;
    /** @var int */
    private $time = 10;
    /** @var Player */
    private $player;
    /** @var int */
    private $average = 0;

    /**
     * StaffGUI constructor.
     * @param StaffTools $plugin
     */
    public function __construct(StaffTools $plugin, InvMenu $menu, Player $player) {
        $this->plugin = $plugin;
        $this->menu = $menu;
        $this->player = $player;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick) : void{
        $cps = $this->plugin->getServer()->getPluginManager()->getPlugin("PreciseCpsCounter");
        $this->time--;
        switch ($this->time) {
            case 9:
                $this->menu->getInventory()->setItem(9, $this->plugin->getCPSGlassPane($cps->getCps($this->player)));
                $this->average += $cps->getCps($this->player);
                break;
            case 8:
                $this->menu->getInventory()->setItem(10, $this->plugin->getCPSGlassPane($cps->getCps($this->player)));
                $this->average += $cps->getCps($this->player);
                break;
            case 7:
                $this->menu->getInventory()->setItem(11, $this->plugin->getCPSGlassPane($cps->getCps($this->player)));
                $this->average += $cps->getCps($this->player);
                break;
            case 6:
                $this->menu->getInventory()->setItem(12, $this->plugin->getCPSGlassPane($cps->getCps($this->player)));
                $this->average += $cps->getCps($this->player);
                break;
            case 5:
                $this->menu->getInventory()->setItem(13, $this->plugin->getCPSGlassPane($cps->getCps($this->player)));
                $this->average += $cps->getCps($this->player);
                break;
            case 4:
                $this->menu->getInventory()->setItem(14, $this->plugin->getCPSGlassPane($cps->getCps($this->player)));
                $this->average += $cps->getCps($this->player);
                break;
            case 3:
                $this->menu->getInventory()->setItem(15, $this->plugin->getCPSGlassPane($cps->getCps($this->player)));
                $this->average += $cps->getCps($this->player);
                break;
            case 2:
                $this->menu->getInventory()->setItem(16, $this->plugin->getCPSGlassPane($cps->getCps($this->player)));
                $this->average += $cps->getCps($this->player);
                break;
            case 1:
                $this->menu->getInventory()->setItem(17, $this->plugin->getCPSGlassPane($cps->getCps($this->player)));
                $this->average += $cps->getCps($this->player);
                break;
            case 0:
                $this->menu->getInventory()->setItem(22, Item::get(Item::SKULL, 3, 1)->setCustomName(C::RESET . C::GREEN . $this->player->getName() . " CPS Test")->setLore([C::WHITE . "Average: " . round($this->average / 9) . " CPS"]));
                $this->plugin->getScheduler()->cancelTask($this->getTaskId());
                break;

        }
    }

}
