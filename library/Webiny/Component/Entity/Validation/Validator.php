<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity\Validation;

use Webiny\Component\Entity\Attribute\AttributeAbstract;
use Webiny\Component\StdLib\SingletonTrait;
use Webiny\Component\StdLib\StdLibTrait;

/**
 * This class is responsible for validating given value against given attribute's validation rules
 *
 * @package   Webiny\Coomponent\Entity\Validation
 */

class Validator
{
	use SingletonTrait, StdLibTrait;

	private $_validators;

	public function validate(AttributeAbstract $attribute, $value) {
		// First build validation rules
		$this->_validators = $this->arr(Builder::getInstance()->buildValidators($attribute->validation()));

		$status = true;
		$errors = [];

		// Validate attribute against the set of rules
		foreach ($this->_validators as $type => $op) {

			// skip empty non-required fields
			if(!$this->_validators->keyExists('required') && $value == '') {
				continue;
			}

			switch ($type) {
				case 'required':
					if($value == '') {
						$status = false;
						$errors[] = $op['message'];
					}
					break;

				case 'minlength':
					if(strlen($value) < $op['value']) {
						$status = false;
						$errors[] = $op['message'];
					}
					break;

				case 'maxlength':
					if(strlen($value) > $op['value']) {
						$status = false;
						$errors[] = $op['message'];
					}
					break;

				case 'number':
					if(!is_numeric($value)) {
						$status = false;
						$errors[] = $op['message'];
					}
					break;

				case 'email':
					if(!checkEmail($value)) {
						$status = false;
						$errors[] = $op['message'];
					}
					break;

				case 'range':
					$rangeData = explode(",", $op['value']);
					$rangeMin = (int)trim($rangeData[0]);
					$rangeMax = (int)trim($rangeData[1]);

					if($value > $rangeMax || $value < $rangeMin) {
						$status = false;
						$errors[] = $op['message'];
					}
					break;

				case 'url':
					if(!preg_match('/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/',
								   $value)
					) {
						$status = false;
						$errors[] = $op['message'];
					}
					break;

				default:
					throw new ValidationException(ValidationException::INVALID_VALIDATOR_TYPE, [$type]);
					break;
			}
		}

		if(!$status){
			$ex = new ValidationException(ValidationException::ATTRIBUTE_VALIDATION_FAILED, [$attribute->attr()]);
			throw $ex->setErrorMessages($errors);
		}
		return true;
	}
}