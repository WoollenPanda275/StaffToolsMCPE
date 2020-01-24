<?php

namespace senpayeh\stafftools;
use muqsit\invmenu\InvMenu;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat as C;
use senpayeh\stafftools\tasks\CPS;

class StaffGUI {

    /** @var StaffTools */
    private $plugin;

    /**
     * StaffGUI constructor.
     * @param StaffTools $plugin
     */
    public function __construct(StaffTools $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param Player $sender
     * @param Player $who
     */
    public function openGUI(Player $sender, Player $who) : void{
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->readonly();
        $menu->setName(C::BLUE . $who->getName() . "'s profile");
        for ($i = 0; $i <= 26; ++$i) {
            $menu->getInventory()->setItem($i, Item::get(Item::STAINED_GLASS_PANE, 0, 1)->setCustomName(C::RESET . " "));
        }
        $menu->getInventory()->setItem(5, $this->plugin->getGlassPane($who));
        $menu->getInventory()->setItem(10, Item::get(Item::STONE_PICKAXE, 0, 1)->setCustomName(C::RESET . C::GOLD . "Start CPS Test"));
        $menu->getInventory()->setItem(12, Item::get(Item::ICE, 0, 1)->setCustomName($this->plugin->isFrozenBlock($who)));
        $menu->getInventory()->setItem(14, Item::get(Item::WRITTEN_BOOK, 0, 1)->setCustomName(C::RESET . C::DARK_GREEN . "Toggle Logs"));
        $menu->getInventory()->setItem(16, Item::get(Item::FIREWORKS, 0, 1)->setCustomName(C::RESET . C::DARK_GRAY . "Ping " . $who->getPing()));
        $this->plugin->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (int $currentTick) use ($menu, $who) : void{
            $menu->getInventory()->setItem(16, Item::get(Item::FIREWORKS, 0, 1)->setCustomName(C::RESET . C::DARK_GRAY . "Ping: " . $who->getPing()));
        }), 20);
        $menu->getInventory()->setItem(23, $this->plugin->getGlassPane($who));
        $menu->getInventory()->setItem(18, Item::get(Item::DYE, 1, 1)->setCustomName(C::RESET . C::RED . "Leave ToolsGUI"));
        $menu->setListener(function(Player $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) use ($who, $menu) : bool{
            switch ($itemClicked->getId()) {
                case Item::STONE_PICKAXE:
                    if ($this->plugin->getServer()->getPluginManager()->getPlugin("PreciseCpsCounter") == null) {
                        $player->sendMessage(C::RED . "PreciseCpsCounter by @luca28pet is required to use this feature!");
                        $player->removeWindow($action->getInventory());
                        return false;
                    }
                    $player->removeWindow($action->getInventory());
                    $cpsmenu = InvMenu::create(InvMenu::TYPE_CHEST);
                    $cpsmenu->readonly();
                    $cpsmenu->setName(C::BLUE . "[StaffTools] CPS Test");
                    $this->plugin->getScheduler()->scheduleRepeatingTask(new CPS($this->plugin, $cpsmenu, $who), 20);
                    $cpsmenu->send($player);
                    return false;
                    break;
                case Item::ICE:
                    if (!$this->plugin->isFrozen($who)) {
                        $menu->getInventory()->setItem(12, Item::get(Item::ICE, 0, 1)->setCustomName(C::RESET . C::YELLOW . "Unfreeze"));
                        $this->plugin->setFrozen($who);
                        $player->sendMessage(C::BLUE . "[StaffTools] " . C::WHITE . "You froze " . C::GREEN . $who->getName() . C::WHITE . ".");
                    } else {
                        $menu->getInventory()->setItem(12, Item::get(Item::ICE, 0, 1)->setCustomName(C::RESET . C::YELLOW . "Freeze"));
                        $this->plugin->removeFreeze($who);
                        $player->sendMessage(C::BLUE . "[StaffTools] " . C::WHITE . "You unfroze " . C::GREEN . $who->getName() . C::WHITE . ".");
                    }
                    return false;
                    break;
                case Item::WRITTEN_BOOK:
                    if (!$this->plugin->isLogged($who)) {
                        $menu->getInventory()->setItem(5, Item::get(Item::STAINED_GLASS_PANE, 13, 1)->setCustomName(C::RESET . C::DARK_GREEN . "ON"));
                        $menu->getInventory()->setItem(23, Item::get(Item::STAINED_GLASS_PANE, 13, 1)->setCustomName(C::RESET . C::DARK_GREEN . "ON"));
                        $this->plugin->setLogged($who);
                    } else {
                        $menu->getInventory()->setItem(5, Item::get(Item::STAINED_GLASS_PANE, 14, 1)->setCustomName(C::RESET . C::RED . "OFF"));
                        $menu->getInventory()->setItem(23, Item::get(Item::STAINED_GLASS_PANE, 14, 1)->setCustomName(C::RESET . C::RED . "OFF"));
                        $this->plugin->removeLogged($who);
                    }
                    return false;
                    break;
                default:
                    $player->removeWindow($action->getInventory());
                    return false;
                    break;
            }
        });
        $menu->send($sender);
    }

}
