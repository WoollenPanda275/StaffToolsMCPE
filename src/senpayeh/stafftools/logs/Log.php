<?php

namespace senpayeh\stafftools\logs;
use pocketmine\utils\Config;
use senpayeh\stafftools\StaffTools;

class Log {

    /** @var StaffTools */
    private $plugin;

    /**
     * Log constructor.
     * @param StaffTools $plugin
     */
    public function __construct(StaffTools $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param string $name
     * @param int $dist
     */
    public function registerLog(string $name, int $dist) : void{
        if (!is_dir($this->plugin->getDataFolder() . "logs/")){
            @mkdir($this->plugin->getDataFolder() . "logs/", 0777, true);
        }
        $file = new Config($this->plugin->getDataFolder() . "logs/" . $name . ".txt", CONFIG::ENUM);
        $file = file_get_contents($this->plugin->getDataFolder() . "logs/" . $name . ".txt");
        file_put_contents($this->plugin->getDataFolder() . "logs/" . $name . ".txt", "[" . date("H:i:s m/d/Y") . "] " . $name . " detected for REACH (" . round($dist) . " blocks)\n",FILE_APPEND | LOCK_EX);
    }

}