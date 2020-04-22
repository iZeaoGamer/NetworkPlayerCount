<?php
/* Copyright (c) 2020 xxAROX. All rights reserved. */
namespace xxAROX\NetworkPlayerCount\task;
use pocketmine\scheduler\Task;
use xxAROX\NetworkPlayerCount\Main;


/**
 * Class StartTask
 * @package xxAROX\NetworkPlayerCount\task
 * @author xxAROX
 * @date 21.04.2020 - 19:38
 * @project NetworkPlayerCount
 */
class StartTask extends Task
{
	public function onRun(int $currentTick){
		if (Main::$isQueryDone) {
			Main::$isQueryDone = FALSE;
			Main::getInstance()->getServer()->getAsyncPool()->submitTask(new FetchPlayerAsyncTask());
		}
	}
}
