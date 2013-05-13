<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject\ArrayObject;

use Webiny\StdLib\Exception\ExceptionAbstract;

/**
 * ArrayObject Exception class.
 *
 * @package         Webiny\StdLib\StdObject\ArrayObject
 */
class ArrayObjectException extends ExceptionAbstract
{
	/**
	 * Constants defining the exception codes.
	 */
	const MSG_INVALID_PARAM = 101;
	const MSG_INVALID_COMBINE_COUNT = 102;
	const MSG_INVALID_COUNT_VALUES = 103;
	const MSG_PARAM_VALUE_OUT_OF_SCOPE = 104;
	const MSG_MULTIDIM_SORT = 105;
	const MSG_VALUE_NOT_PRESENT = 106;

	/**
	 * An array containing the exception code for key and exception message for value.
	 *
	 * @var array
	 */
	static protected $_messages = [
		101 => 'Invalid parameter type provided for %s param. Parameter type must be within this range [%s].',
		102 => 'When combining arrays, both arrays must have an equal number of items.',
		103 => 'Method countValues() can be performed on an ArrayObject that contains only STRING and INTEGER values.',
		104 => 'The given value for param %s is not within an allowed scope. The value should be %s.',
		105 => 'You can only sort a multi-dimensional array.',
		106 => '%s is not present in %s.'
	];

}