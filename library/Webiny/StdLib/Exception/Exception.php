<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link         http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright    Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license      http://www.webiny.com/framework/license
 * @package      WebinyFramework
 */
namespace Webiny\StdLib\Exception;


use Webiny\StdLib\StdObjectTrait;
use Webiny\StdLib\ValidatorTrait;

/**
 * Standard exception class.
 *
 * @package             Webiny\StdLib\Exception
 */
class Exception extends ExceptionAbstract
{
	use ValidatorTrait, StdObjectTrait;

	/**
	 * Set the exception message that will be thrown.
	 * Current line and file will be set as exception origin.
	 *
	 * @param string $message
	 * @param null   $params
	 */
    public function __construct($message, $params = null) {
		parent::__construct($message, $params);
    }
}