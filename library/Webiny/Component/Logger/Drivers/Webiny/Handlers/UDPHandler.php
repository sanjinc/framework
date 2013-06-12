<?php

namespace Webiny\Component\Logger\Drivers\Webiny\Handlers;

use Webiny\Bridge\Logger\LoggerException;
use Webiny\Bridge\Logger\Webiny\HandlerAbstract;
use Webiny\Bridge\Logger\Webiny\Record;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\UrlObject\UrlObject;
use Webiny\WebinyTrait;

/**
 * @package Webiny\Component\Logger\Drivers\Webiny\Handlers
 */
class UDPHandler extends HandlerAbstract
{
	use StdLibTrait, WebinyTrait;

	/**
	 * Host URL
	 * @var UrlObject
	 */
	private $_host;

	public function __construct($levels = [], $bubble = true, $buffer = false, UrlObject $host = null) {
		parent::__construct($levels, $bubble, $buffer);

		if($this->isNull($host)) {
			$host = $this->webiny()->getConfig()->components->logger->handlers->udp->host;
			$host = $this->url($host);
		}
		$this->_host = $host;
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
			$fp = fsockopen("udp://".$this->_host->getHost(), $this->_host->getPort(), $errno, $errstr);
			if(!$fp) {
				throw new LoggerException('Could not open socket for writing!');
			}

			if(!$this->isString($record->formatted) && !$this->isStringObject($record->formatted)){
				throw new LoggerException('Formatted record must be a string or StringObject!');
			}

			fwrite($fp, StdObjectWrapper::toString($record->formatted));
			fclose($fp);
		} catch (Exception $e) {
			throw new LoggerException($e->getMessage());
		}
	}

	protected function _getDefaultFormatter() {
		return null;
	}
}