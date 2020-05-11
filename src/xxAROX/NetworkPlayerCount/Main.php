<?php
/* Copyright (c) 2020 xxAROX. All rights reserved. */
namespace xxAROX\NetworkPlayerCount;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use xxAROX\NetworkPlayerCount\task\StartTask;


/**
 * Class Main
 * @package xxAROX\NetworkPlayerCount
 * @author xxAROX
 * @date 21.04.2020 - 19:22
 * @project NetworkPlayerCount
 */
class Main extends PluginBase{
	public static $playerCount = 0;
	public static $isQueryDone = TRUE;


	/**
	 * Function onEnable
	 * @return void
	 */
	public function onEnable(): void{
		$changed = false;
		$config = $this->getConfig();

		if (!$config->exists("host")) {
			$config->set("host", "stimomc.de");
			$changed = true;
		}
		if (!$config->exists("ports")) {
			$config->set("ports", [3565]);
			$changed = true;
		}
		if ($changed) $config->save();
		$this->getScheduler()->scheduleRepeatingTask(new StartTask($this->getConfig()->get("host", "stimomc.de"), $this->getConfig()->get("ports", [3565])), 20 * 5);
	}

	/**
	 * Function getConfig
	 * @return Config
	 */
	public function getConfig(): Config{
		$config = parent::getConfig();
		$config->reload();
		return $config;
	}

	/**
	 * Function getTotalNetworkPlayers
	 * @return int
	 */
	public static function getTotalNetworkPlayers(): int{
		return self::$playerCount;
	}

	/**
	 * Function getNetworkPlayers
	 * @param int $port
	 * @return int
	 */
	public static function getNetworkPlayers(int $port): int{
		return self::$playerCount[$port] ?? 0;
	}
}
