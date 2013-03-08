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
  * WebinyFramework exception interface.
  * Use it if you want to throw custom exceptions.
  *
  * @package		WebinyFramework
  * @category		StdLib
  * @subcategory	Exception
  */
 interface ExceptionInterface
 {
	 /**
	  * Constructor
	  * Set the exception message that will be thrown.
	  * Current line and file will be set as exception origin.
	  *
	  * @param string $message
	  */
	 public function __construct($message);
 }