<?php

namespace Senpayeh\StaffTools\Logs;
use Senpayeh\StaffTools\Main;
use Senpayeh\StaffTools\ToolsGUI;
use pocketmine\utils\Config;
use pocketmine\Player;

class Files {

  private $plugin;

  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }

  public function log(int $dist, Player $player) {
    if (!is_dir($this->plugin->getDataFolder() . "logs/")){
			@mkdir($this->plugin->getDataFolder() . "logs/", 0777, true);
		}
		$name = $player->getName();
		$file = new Config($this->plugin->getDataFolder() . "logs/" . $name . ".txt", CONFIG::ENUM);
    $file = file_get_contents($this->plugin->getDataFolder() . "logs/" . $name . ".txt");
		file_put_contents($this->plugin->getDataFolder() . "logs/" . $name . ".txt", "[" . date("H:i:s m/d/Y") . "] " . $player->getName() . " detected for hitbox (" . round($dist) . " blocks) - Ping: " . $player->getPing() . "\n", FILE_APPEND | LOCK_EX);
  }

  public function getLines(Player $player) : int{
    $name = $player->getName();
    $linecount = 0;
    if (file_exists($this->plugin->getDataFolder() . "logs/" . $name . ".txt")) {
      $handle = fopen($this->plugin->getDataFolder() . "logs/" . $name . ".txt", "r");
        while (!feof($handle)) {
          $line = fgets($handle);
          $linecount++;
        }
      return $linecount;
    } else {
      return 0;
    }
  }

}
