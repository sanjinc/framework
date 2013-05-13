<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\StdLib\Exception;

use Webiny\StdLib\StdObjectTrait;
use Webiny\StdLib\ValidatorTrait;

/**
 * Exception abstract class.
 * Extend this class if you wish do create your own exception class.
 *
 * @package         Webiny\StdLib\Exception
 */
abstract class ExceptionAbstract extends \Exception implements ExceptionInterface
{
	use StdObjectTrait, ValidatorTrait;

	const MSG_BAD_FUNC_CALL = 1;
	const MSG_BAD_METHOD_CALL = 2;
	const MSG_INVALID_ARG = 3;
	const MSG_INVALID_ARG_LENGTH = 4;
	const MSG_ARG_OUT_OF_RANGE = 5;

	/**
	 * Built-in exception messages.
	 * Built-in codes range from 1-100 so make sure you custom codes are out of that range.
	 *
	 * @var array
	 */
	static private $_coreMessages = [
		1 => 'Bad function call.',
		2 => 'Bad method call.',
		3 => 'Invalid argument provided. %s must be type of %s.',
		4 => 'Invalid argument provided. %s must be %s.',
		5 => 'Defined value for %s argument if out of the valid range.'
	];


	/**
	 * Create an instance of exception class.
	 *
	 * @param string|int       $message Message you what to throw. If $message is type of integer,
	 *                                  than the method will treat that as an exception code.
	 * @param null|array       $params  If message has variables inside, send an array of values using this argument,
	 *                                  and the variables will be replaced with those values in the same order they appear.
	 */
	public function __construct($message, $params = null) {
		$code = 0;
		if($this->isNumber($message)) {
			$code = $message;
			if($code < 100) {
				// built-in range
				if($this->is(self::$_coreMessages[$code])) {
					$message = self::$_coreMessages[$code];
				} else {
					$message = 'Unknown exception message for the given code "' . $code . '".';
				}
			} else {
				if($this->is(static::$_messages[$code])) {
					$message = static::$_messages[$code];
				} else {
					$message = 'Unknown exception message for the given code "' . $code . '".';
				}
			}
		}

		if(!$this->isNull($params)) {
			$message = $this->str($message)->format($params)->val();
		}

		parent::__construct($message, $code);
	}

}