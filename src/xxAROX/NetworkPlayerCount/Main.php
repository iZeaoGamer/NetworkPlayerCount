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
	public static $playerCount = [];
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
		if (!$config->exists("path")) {
			$config->set("path", "/home/mcpe/Proxy/config.yml");
			$changed = true;
		}
		if ($changed) $config->save();
		$this->getScheduler()->scheduleRepeatingTask(new StartTask($this->getConfig()->get("host", "stimomc.de"), $this->getConfig()->get("path", "/home/mcpe/Proxy/config.yml")), 20 * 5);
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
		return self::$playerCount["all"];
	}

	/**
	 * Function getNetworkPlayers
	 * @return int
	 */
	public static function getNetworkPlayers(string $bungeeServerName): int{
		return self::$playerCount[$bungeeServerName] ?? 0;
	}
}
