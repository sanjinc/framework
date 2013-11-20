<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity;

use Webiny\Component\Entity\Attribute\TypeAbstract;
use Webiny\Component\StdLib\StdLibTrait;


/**
 * Entity
 * @package \Webiny\Component\Entity
 */

abstract class EntityAbstract
{
	use StdLibTrait;

	protected $_validation;
	protected $_properties;

	protected abstract function _entityStructure();

	public function __construct(){
		$this->_validation = $this->arr();
		$this->_properties = $this->arr();

		$this->_entityStructure();
	}

	public function populate($data){

		/** @var $object TypeAbstract */
		foreach($this->_properties as $attributeName => $object){
			if(isset($data[$object->name()])){
				$dataValue = $data[$object->name()];
				try{
					$object->validate($dataValue)->value($dataValue);
				} catch(\Exception $e){
					$this->_validation[$attributeName] = $e;
				}
			}
		}

		if($this->_validation->count() > 0){
			return false;
		}

		return $this;
	}

	public function getInvalidProperties(){
		return $this->_validation;
	}

	public function getAttribute($attribute){
		return $this->_properties[$attribute];
	}

	/**
	 * @param $attribute
	 *
	 * @return EntityAttributeBuilder
	 */
	protected function attr($attribute){
		return EntityAttributeBuilder::getInstance()->setContext($this->_properties, $attribute);
	}
}