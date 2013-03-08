<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package	  WebinyFramework
 */
namespace WF\StdLib\Exception;


/**
 * Standard exception class.
 *
 * @package			WebinyFramework
 * @category		StdLib
 * @subcategory		Exception
 */
class Exception extends ExceptionAbstract
{

	/**
	* Set the exception message that will be thrown.
	* Current line and file will be set as exception origin.
	*
	* @param string $message
	*/
	public function __construct($message)
	{
		parent::__construct($message);
	}
}