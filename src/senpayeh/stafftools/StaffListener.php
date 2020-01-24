<?php

namespace senpayeh\stafftools;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;
use senpayeh\stafftools\logs\Log;

class StaffListener implements Listener {

    /** @var StaffTools */
    private $plugin;

    /**
     * @param StaffTools $plugin
     */
    public function __construct(StaffTools $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param EntityDamageByEntityEvent $event
     */
    public function onEntityDamage(EntityDamageByEntityEvent $event) {
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        $item = $damager->getInventory()->getItemInHand();
        if ($entity instanceof Player and $damager instanceof Player) {
            if ($item->getId() == Item::COMPASS and $item->getCustomName() == C::RESET . C::DARK_GREEN . "Op" . C::GREEN . "en " . C::DARK_GREEN . "Tools" . C::GREEN . "GUI") {
                $gui = new StaffGUI($this->plugin);
                $gui->openGUI($damager, $entity);
                $event->setCancelled();
            }
            if ($this->plugin->isLogged($damager)) {
                if ($damager->getGamemode() == Player::SURVIVAL or $damager->getGamemode() == Player::ADVENTURE) {
                    if ($entity->distance($damager) >= (int)$this->plugin->config->get("minimum-distance")) {
                        $logs = new Log($this->plugin);
                        $logs->registerLog($damager->getName(), $entity->distance($damager->getPosition()));
                    }
                }
            }
        }
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if ($this->plugin->config->get("default-register-logs") == "true") {
            $this->plugin->setLogged($player);
        }
    }

}