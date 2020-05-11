<?php
/* Copyright (c) 2020 xxAROX. All rights reserved. */
namespace xxAROX\NetworkPlayerCount\task;
use pocketmine\utils\Config;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use xxAROX\NetworkPlayerCount\Main;


/**
 * Class FetchPlayerAsyncTask
 * @package xxAROX\NetworkPlayerCount\task
 * @author xxAROX
 * @date 21.04.2020 - 19:24
 * @project NetworkPlayerCount
 */
class FetchPlayerAsyncTask extends AsyncTask
{
	protected $host;
	protected $ports;

	/**
	 * FetchPlayerAsyncTask constructor
	 * @param string $host
	 * @param int[] $ports
	 */
	public function __construct(string $host, array $ports){
		$this->host = $host;
		$this->ports = $ports;
	}

	/**
	 * Function getQueryInfo
	 * @param string $host
	 * @param int $port
	 * @param int $timeout
	 * @return array|bool
	 */
	public function getQueryInfo(string $host, int $port, int $timeout=4){
		$socket = @fsockopen("udp://" . $host, $port, $errno, $errstr, $timeout);

		if ($errno || $socket === FALSE) {
			return FALSE;
		}
		stream_Set_Timeout($socket, $timeout);
		stream_Set_Blocking($socket, TRUE);
		$randInt = mt_rand(1, 999999999);
		$reqPacket = "\x01";
		$reqPacket .= pack("Q*", $randInt);
		$reqPacket .= "\x00\xff\xff\x00\xfe\xfe\xfe\xfe\xfd\xfd\xfd\xfd\x12\x34\x56\x78";
		$reqPacket .= pack("Q*", 0);
		fwrite($socket, $reqPacket, strlen($reqPacket));
		$response = fread($socket, 4096);
		fclose($socket);

		if (empty($response) or $response === FALSE) {
			return FALSE;
		}
		if (substr($response, 0, 1) !== "\x1C") {
			return FALSE;
		}
		$serverInfo = substr($response, 35);
		//$serverInfo = preg_replace("#ยง.#", "", $serverInfo);
		$serverInfo = explode(';', $serverInfo);

		return [
			"motd" => $serverInfo[1],
			"count" => $serverInfo[4],
			"version" => $serverInfo[3]
		];
	}

	/**
	 * Function onRun
	 * @return void
	 */
	public function onRun(){
		$playercount = ["all" => 0];

		foreach ($this->ports as $port) {
			$result = $this->getQueryInfo($this->host, $port);

			if (!empty($result["motd"])) {
				$playercount["all"] += $result["count"];
				$playercount[$port]  = $result["count"];
			}
		}
		$this->setResult($playercount);
	}

	/**
	 * Function onCompletion
	 * @param Server $server
	 * @return void
	 */
	public function onCompletion(Server $server){
		parent::onCompletion($server);
		$result = $this->getResult();

		Main::$isQueryDone = TRUE;
		Main::$playerCount = $result;
	}
}
