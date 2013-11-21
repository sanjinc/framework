<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity\Validation;

use Webiny\Component\StdLib\SingletonTrait;

/**
 * Description
 *
 * @package   Webiny\Component\Entity\Validation
 */

class Builder
{
	use SingletonTrait;

	public function buildValidators($validationRules) {
		$validators = [];

		foreach ($validationRules as $rule) {
			$errorMessage = '';
			if(strpos($rule, ':') !== false) {
				$ruleData = explode(':', $rule);
				$ruleName = trim($ruleData[0]);

				$ruleData2 = explode("|", $ruleData[1]);
				$ruleValue = '';
				if(count($ruleData2) > 1) {
					$ruleValue = $ruleData2[0];
					$errorMessage = isset($ruleData2[1]) ? $ruleData2[1] : '';
				} else {
					if(stripos($ruleData[1], '|') !== false) {
						$errorMessage = isset($ruleData2[0]) ? $ruleData2[0] : '';
					} else {
						$ruleValue = $ruleData2[0];
					}
				}
			} else {
				$ruleData = explode("|", $rule);
				$ruleName = trim($ruleData[0]);
				$ruleValue = '';
				$errorMessage = isset($ruleData[1]) ? $ruleData[1] : '';
			}

			$errorMessageArray = array
			(
				'required'  => 'This field is required.',
				'minlength' => 'The given value is too short.',
				'maxlength' => 'The given value is too long.',
				'number'    => 'This field takes only numbers.',
				'email'     => 'Invalid email address.',
				'range'     => 'The value is not within a valid range.',
				'url'       => 'The value must start with http://'
			);

			switch ($ruleName) {
				case 'required':
					$validators['required'] = array
					(
						'value'   => 'true',
						'message' => ($errorMessage != '') ? $errorMessage : $errorMessageArray['required']
					);
					break;

				case 'minlength':
					$validators['minlength'] = array
					(
						'value'   => $ruleValue,
						'message' => ($errorMessage != '') ? $errorMessage : $errorMessageArray['minlength']
					);
					break;

				case 'maxlength':
					$validators['maxlength'] = array
					(
						'value'   => $ruleValue,
						'message' => ($errorMessage != '') ? $errorMessage : $errorMessageArray['maxlength']
					);
					break;

				case 'number':
					$validators['number'] = array
					(
						'value'   => 'true',
						'message' => ($errorMessage != '') ? $errorMessage : $errorMessageArray['number']
					);
					break;

				case 'email':
					$validators['email'] = array
					(
						'value'   => 'true',
						'message' => ($errorMessage != '') ? $errorMessage : $errorMessageArray['email']
					);
					break;

				case 'range':
					//range:[start,end]
					$validators['range'] = array
					(
						'value'   => $ruleValue,
						'message' => ($errorMessage != '') ? $errorMessage : $errorMessageArray['range']
					);
					break;

				case 'url':
					$validators['url'] = array
					(
						'value'   => 'true',
						'message' => ($errorMessage != '') ? $errorMessage : $errorMessageArray['url']
					);
					break;

				default:
					throw new \Exception('Validator type invalid: ' . $ruleName);
					break;
			}
		}

		return $validators;
	}
}