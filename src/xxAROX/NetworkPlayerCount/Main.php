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
	public static $playerCount = 0;
	public static $isQueryDone = TRUE;

	public function onEnable(): void{
		$this->getScheduler()->scheduleRepeatingTask(new StartTask(), 20 * 5);
	}

	public static function getNetworkPlayers(): int{
		return self::$playerCount;
	}
}
