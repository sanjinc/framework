<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link         http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright    Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license      http://www.webiny.com/framework/license
 * @package      WebinyFramework
 */

namespace WF\StdLib\StdObject;

/**
 * Standard object interface.
 *
 * @package         WebinyFramework
 * @category        StdLib
 * @subcategory        StdObject
 */

interface StdObjectInterface
{
	/**
	 * Constructor.
	 * Set standard object value.
	 *
	 * @param mixed $value    Passed by reference.
	 */
	function __construct(&$value);

	/**
	 * Return current standard objects value.
	 *
	 * @return mixed
	 */
	function getValue();

	/**
	 * Returns the current standard object instance.
	 *
	 * @return mixed
	 */
	function getObject();

	/**
	 * The update value method is called after each modifier method.
	 * It updates the current value of the standard object.
	 *
	 * @param mixed $value    Passed by reference.
	 */
	function updateValue(&$value);

	/**
	 * To string implementation.
	 *
	 * @return mixed
	 */
	function __toString();
}