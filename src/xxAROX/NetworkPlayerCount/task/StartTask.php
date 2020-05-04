<?php
/* Copyright (c) 2020 xxAROX. All rights reserved. */
namespace xxAROX\NetworkPlayerCount\task;
use pocketmine\Server;
use pocketmine\scheduler\Task;
use xxAROX\NetworkPlayerCount\Main;


/**
 * Class StartTask
 * @package xxAROX\NetworkPlayerCount\task
 * @author xxAROX
 * @date 21.04.2020 - 19:38
 * @project NetworkPlayerCount
 */
class StartTask extends Task{
	protected $host;
	protected $path;

	/**
	 * StartTask constructor
	 * @param string $host
	 * @param string $path
	 */
	public function __construct(string $host, string $path){
		$this->host = $host;
		$this->path = $path;
	}

	public function onRun(int $currentTick){
		if (Main::$isQueryDone) {
			Main::$isQueryDone = FALSE;
			Server::getInstance()->getAsyncPool()->submitTask(new FetchPlayerAsyncTask($this->host, $this->path));
		}
	}
}
