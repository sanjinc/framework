<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity\Validation;

/**
 * Description
 *
 * @package   Webiny\Component\Entity\Validation
 */

class ValidationResult
{

	protected $_valid;
	protected $_errors;

	public function __construct($valid, $errors = []) {
		$this->_valid = $valid;
		$this->_errors = $errors;
	}

	/**
	 * Is result valid
	 * @return boolean
	 */
	public function isValid() {
		return $this->_valid;
	}

	/**
	 * Get validation error messages
	 * @return array
	 */
	public function getErrors() {
		return $this->_errors;
	}
}