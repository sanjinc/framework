<?php

namespace Webiny\Component\Logger\Drivers\Webiny\Handlers;

use Webiny\Bridge\Logger\LoggerException;
use Webiny\Bridge\Logger\Webiny\HandlerAbstract;
use Webiny\Bridge\Logger\Webiny\Record;
use Webiny\Component\Registry\RegistryTrait;
use Webiny\StdLib\StdLibTrait;

/**
 * @package Webiny\Component\Logger\Drivers\Webiny\Handlers
 */
class UDPHandler extends HandlerAbstract
{
	use StdLibTrait, RegistryTrait;

	private $_host;
	private $_port;

	public function __construct($levels = [], $bubble = true, $buffer = false, $host = null) {
		parent::__construct($levels, $bubble, false);

		if($this->isNull($host)) {
			$host = $this->registry()->webiny->components->logger->handlers->udp->host;
		}

		list($this->_host, $this->_port) = explode(':', $host);
	}

	/**
	 * Writes the record to destination
	 *
	 * @param Record $record
	 *
	 * @throws LoggerException
	 * @return void
	 */
	protected function write(Record $record) {
		try {
			$fp = fsockopen("udp://$this->_host", $this->_port, $errno, $errstr);
			if(!$fp) {
				return;
			}

			$message = $this->isArray($record->formatted) ? $this->jsonEncode($record->formatted) : $record->formatted;

			fwrite($fp, $message);
			fclose($fp);
		} catch (Exception $e) {
			throw new LoggerException($e->getMessage());
		}
	}

	protected function _getDefaultFormatter() {
		return null;
	}
}