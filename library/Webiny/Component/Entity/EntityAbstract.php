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
use Webiny\Component\Entity\Validation\ValidationException;
use Webiny\Component\StdLib\StdLibTrait;


/**
 * Entity
 * @package \Webiny\Component\Entity
 */

abstract class EntityAbstract
{
	use StdLibTrait;

	protected $_attributes;

	protected abstract function _entityStructure();

	public function __construct(){
		$this->_attributes = $this->arr();

		$this->_entityStructure();
	}

	public function populate($data){
		$validation = $this->arr();
		/** @var $object TypeAbstract */
		foreach($this->_attributes as $attributeName => $object){
			if(isset($data[$object->name()])){
				$dataValue = $data[$object->name()];
				try{
					$object->validate($dataValue)->value($dataValue);
				} catch(ValidationException $e){
					$validation[$attributeName] = $e;
				}
			}
		}

		if($validation->count() > 0){
			$ex = new EntityException(EntityException::VALIDATION_FAILED, [$validation->count()]);
			throw $ex->setInvalidAttributes($validation);
		}

		return $this;
	}

	public function getAttribute($attribute){
		return $this->_attributes[$attribute];
	}

	public function getAttributes(){
		return $this->_attributes;
	}

	/**
	 * @param $attribute
	 *
	 * @return EntityAttributeBuilder
	 */
	protected function attr($attribute){
		return EntityAttributeBuilder::getInstance()->setContext($this->_attributes, $attribute);
	}
}