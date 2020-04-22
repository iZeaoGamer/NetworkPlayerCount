<?php
/* Copyright (c) 2020 xxAROX. All rights reserved. */
namespace xxAROX\NetworkPlayerCount;
use pocketmine\plugin\PluginBase;
use xxAROX\NetworkPlayerCount\task\StartTask;


/**
 * Class Main
 * @package xxAROX\NetworkPlayerCount
 * @author xxAROX
 * @date 21.04.2020 - 19:22
 * @project NetworkPlayerCount
 */
class Main extends PluginBase
{
	private static $instance;
	const PREFIX = "§eStimoMC §8» §7";
	private $prefix = self::PREFIX;
	public static $playerCount = 0;
	public static $isQueryDone = TRUE;


	public function onLoad(): void{
		self::$instance = $this;
	}

	public function onEnable(): void{
		$config = $this->getConfig();

		if (!$config->exists("waterdog-config-path")) {
			$config->set("waterdog-config-path", "/home/mcpe/Proxy/config.yml");
		}
		if (!$config->exists("server-address")) {
			$config->set("server-address", "stimomc.de");
		}
		$config->save();
		$this->getScheduler()->scheduleRepeatingTask(new StartTask(), 20 * 5);
	}

	public function onDisable(): void{
	}

	public function getPrefix(): string{
		return $this->prefix;
	}

	public static function getInstance(): self{
		return self::$instance;
	}

	public static function getNetworkPlayers(): int{
		return self::$playerCount;
	}
}
