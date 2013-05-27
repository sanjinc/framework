<?php

namespace Webiny\Component\Logger\Drivers\Webiny\Handlers;

use Webiny\Bridge\Logger\Webiny\HandlerAbstract;
use Webiny\Bridge\Logger\Webiny\Record;
use Webiny\Component\Registry\RegistryTrait;
use Webiny\StdLib\StdLibTrait;

/**
 * NodeJS Logger is responsible for pushing any data to NodeJS Logger application which in turn pushes notifications to
 * Webiny Tray Logger application
 *
 * @package Webiny\Component\Logger\Drivers\Webiny\Handlers
 */
class JsonRpcUdpHandler extends HandlerAbstract
{
	use StdLibTrait, RegistryTrait;

	private $_host;
	private $_port;
	private $_tray;

	public function __construct($levels = [], $bubble = true, $host = null, $tray = null) {
		// Make this handler always buffer all the messages
		parent::__construct($levels, $bubble, true);

		if($this->isNull($host)) {
			$host = $this->registry()->webiny->components->logger->handlers->json_rpc_udp->host;
		}

		if($this->isNull($tray)) {
			$tray = $this->registry()->webiny->components->logger->tray;

			if($this->isInstanceOf($tray, 'Webiny\Component\Config\ConfigObject')){
				$tray = $tray->toArray();
			}

			if(!$this->isArray($tray) && !$this->isArrayObject($tray)) {
				$tray = [$tray];
			}
		}
		
		list($this->_host, $this->_port) = explode(':', $host);
		$this->_tray = $tray;
	}

	/**
	 * Writes the record down to the log of the implementing handler
	 *
	 * @param Record $record
	 *
	 * @return void
	 */
	protected function write(Record $record) {
		try {
			$fp = fsockopen("udp://$this->_host", $this->_port, $errno, $errstr);
			if(!$fp) {
				return;
			}

			$message = $this->isArray($record->formatted) ? json_encode($record->formatted) : $record->formatted;

			fwrite($fp, json_encode($message));
			fclose($fp);
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	protected function _getDefaultFormatter() {
		return new JsonRpcFormatter();
	}
}