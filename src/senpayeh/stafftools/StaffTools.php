<?php

namespace senpayeh\stafftools;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;

class StaffTools extends PluginBase {

    /** @var array */
    public $logs = array();
    /** @var array */
    public $frozen = array();

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents(new StaffListener($this), $this);
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    /**
     * @param Player $player
     */
    public function getCompass(Player $player) : void{
        $compass = Item::get(Item::COMPASS, 0, 1)->setCustomName(C::RESET . C::DARK_GREEN . "Op" . C::GREEN . "en " . C::DARK_GREEN . "Tools" . C::GREEN . "GUI");
        $compass->setNamedTagEntry(new ListTag("ench"));
        $player->getInventory()->addItem($compass);
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isLogged(Player $player) : bool{
        return isset($this->logs[$player->getName()]);
    }

    /**
     * @param Player $player
     * @return Item
     */
    public function getGlassPane(Player $player) : Item{
        if ($this->isLogged($player)) {
            return Item::get(Item::STAINED_GLASS_PANE, 13, 1)->setCustomName(C::RESET . C::DARK_GREEN . "ON");
        } else {
            return Item::get(Item::STAINED_GLASS_PANE, 14, 1)->setCustomName(C::RESET . C::RED . "OFF");
        }
    }

    /**
     * @param int $cps
     * @return Item
     */
    public function getCPSGlassPane(int $cps) : Item{
        if ($cps <= 9) {
            return Item::get(Item::STAINED_GLASS_PANE, 5, 1)->setCustomName(C::RESET . C::GREEN . $cps . " CPS");
        }
        if ($cps >= 10 and $cps <= 14) {
            return Item::get(Item::STAINED_GLASS_PANE, 4, 1)->setCustomName(C::RESET . C::GOLD . $cps . " CPS");
        }
        if ($cps >= 15) {
            return Item::get(Item::STAINED_GLASS_PANE, 14, 1)->setCustomName(C::RESET . C::RED . $cps . " CPS");
        }
    }

    /**
     * @param Player $sender
     * @param Player $victim
     * @param string $reason
     */
    public function sendWarningMessage(Player $sender, Player $victim, string $reason) : void{
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            if ($player->hasPermission("staffmode.warnmessage")) {
                $player->sendMessage(C::BLUE . "[StaffTools] " . C::GREEN . $sender->getName() . C::WHITE . " warned " . C::GREEN . $victim->getName() . C::WHITE . ", reason: " . C::GREEN . $reason);
            }
        }
    }

    /**
     * @param Player $player
     */
    public function setLogged(Player $player) : void{
        $this->logs[$player->getName()] = $player->getName();
    }

    /**
     * @param Player $player
     */
    public function removeLogged(Player $player) : void{
        unset($this->logs[$player->getName()]);
    }

    /**
     * @param Player $player
     */
    public function setFrozen(Player $player) : void{
        $this->frozen[$player->getName()] = $player->getName();
        $player->setImmobile();
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (int $currentTick) use ($player) : void{
            if ($this->isFrozen($player)) {
                $player->sendPopup(C::RED . "You have been frozen! Do not log out.");
            }
        }), 20);
    }

    /**
     * @param Player $player
     */
    public function removeFreeze(Player $player) : void{
        unset($this->frozen[$player->getName()]);
        $player->setImmobile(false);
        $player->sendMessage(C::WHITE . "You have been " . C::GREEN . "unfrozen");
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isFrozen(Player $player) : bool{
        return isset($this->frozen[$player->getName()]);
    }

    /**
     * @param Player $player
     * @return string
     */
    public function isFrozenBlock(Player $player) : string{
        if ($this->isFrozen($player)) {
            return C::RESET . C::YELLOW . "Unfreeze";
        } else {
            return C::RESET . C::YELLOW . "Freeze";
        }
    }

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if (strtolower($command->getName()) == "tools") {
            if ($sender->hasPermission("stafftools.command")) {
                $sender->sendMessage(C::WHITE . "You have been given the " . C::GREEN . "StaffTools Compass" . C::WHITE . ". Left-Click on someone to open the GUI.");
                $this->getCompass($sender);
                return true;
            } else {
                $sender->sendMessage(C::RED . "Invalid permissions to run this command.");
                return false;
            }
        }
    }

}