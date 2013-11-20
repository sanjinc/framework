<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity;

use Webiny\Component\Entity\Attribute\CharType;
use Webiny\Component\Entity\Attribute\DecimalType;
use Webiny\Component\Entity\Attribute\IntegerType;
use Webiny\Component\StdLib\SingletonTrait;


/**
 * EntityBuilder
 * @package Webiny\Component\Entity
 */

class EntityAttributeBuilder
{
	use SingletonTrait;

	/**
	 * @param string    $attribute
	 * @param null 		$name
	 *
	 * @return IntegerType
	 */
	public function integer($attribute, $name = null) {
		return new IntegerType($attribute, $name);
	}

	/**
	 * @param string 	$attribute
	 * @param null 		$name
	 *
	 * @return CharType
	 */
	public function char($attribute, $name = null) {
		return new CharType($attribute, $name);
	}

	/**
	 * @param string	$attribute
	 * @param null 		$name
	 *
	 * @return DecimalType
	 */
	public function decimal($attribute, $name = null) {
		return new DecimalType($attribute, $name);
	}

	public function text($attribute) {

	}

	public function date($attribute) {

	}

	public function select($attribute) {

	}

	public function dynamic($attribute) {

	}

	public function many2one($attribute) {

	}
}