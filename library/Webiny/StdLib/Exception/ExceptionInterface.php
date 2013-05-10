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

/**
 * WebinyFramework exception interface.
 * Use it if you want to throw custom exceptions.
 *
 * @package         Webiny\StdLib\Exception
 */
interface ExceptionInterface
{
	/**
	 * Constructor
	 * Set the exception message that will be thrown.
	 * Current line and file will be set as exception origin.
	 *
	 * Make sure you return:
	 * parent::_construct($message, $params);
	 *
	 * @param string $message
	 * @param null   $params
	 */
    public function __construct($message, $params = null);
}