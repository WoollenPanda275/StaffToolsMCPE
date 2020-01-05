<?php

namespace Senpayeh\StaffTools;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use Senpayeh\StaffTools\Logs\Files;
use pocketmine\utils\TextFormat as C;
use pocketmine\Player;
use function array_filter;

class EventListener implements Listener {

  private $plugin;

  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }

  public function onEntityDamageByEntity(EntityDamageByEntityEvent $event) {
    $damager = $event->getDamager();
    $entity = $event->getEntity();
    if ($damager->getInventory()->getItemInHand()->getCustomName() == C::RESET . C::DARK_GRAY . "Open GUI " . C::LIGHT_PURPLE . ("(LEFT CLICK)")) {
      $tools = new ToolsGUI($this->plugin);
      $tools->openGUI($damager, $entity);
      $event->setCancelled();
    }
  }

  public function onDamage(EntityDamageEvent $event) {
    if ($event instanceof EntityDamageByEntityEvent) {
      $entity = $event->getEntity();
      $damager = $event->getDamager();
      if ($entity instanceof Player and $damager instanceof Player) {
        if ($entity->distance($damager) >= 6) {
          if (in_array($damager->getName(), $this->plugin->logs)) {
            $file = new Files($this->plugin);
            $file->log($entity->distance($damager), $damager);
          }
        }
      }
    }
  }

}
