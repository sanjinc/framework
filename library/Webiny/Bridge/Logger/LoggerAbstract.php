<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Logger;

use Webiny\StdLib\StdLibTrait;

/**
 * Description
 *
 * @package   Webiny\Bridge\Logger
 */

abstract class LoggerAbstract implements LoggerInterface
{
	use StdLibTrait;

	protected $_channelName = '';

	protected $_handlers = [];
	protected $_processors = [];
	protected $_formatter = null;

	abstract protected function __construct($channelName);

	public function addHandler(LoggerHandlerAbstract $handler){
		$this->arr($this->_handlers)->prepend($handler);
	}

	public function addProcessor($processor){
		$this->arr($this->_processors)->prepend($processor);
	}

	public static function getInstance($channelName) {
		return new static($channelName);
	}

}