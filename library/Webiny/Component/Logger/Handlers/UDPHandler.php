<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */


namespace Webiny\Component\Logger\Handlers;

use Webiny\Component\Logger\LoggerException;
use Webiny\Component\Logger\Handlers\HandlerAbstract;
use Webiny\Component\Logger\Record;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\StdObjectWrapper;
use Webiny\Component\StdLib\StdObject\UrlObject\UrlObject;
use Webiny\WebinyTrait;

/**
 * UDP handler is used for sending UDP messages to host and port specified in the system configuration file.<br />
 * Example of the config:
 *
 * components:
 *		logger:
 *			handlers:
 *				udp:
 *					host: 192.168.1.10:41234
 *
 * @package Webiny\Component\Logger\Handlers
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