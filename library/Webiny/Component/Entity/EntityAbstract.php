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
	use StdLibTrait, EntityAttributeBuilderTrait, EntityAttributeMethodsTrait;

	protected $_validation;
	protected $_currentAttribute = null;
	protected $_properties;

	public function __construct(){
		$this->_validation = $this->arr();
		$this->_wbEntityStructure();

		foreach($this->_properties as $attribute => $object){
			$this->{'_'.$attribute} = $object;
		}
	}

	public function populate($data){

		/** @var $object TypeAbstract */
		foreach($this->_properties as $attributeName => $object){
			if(isset($data[$object->getName()])){
				$dataValue = $data[$object->getName()];
				try{
					$object->validate($dataValue)->setValue($dataValue);
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

	protected function attr($attribute){
		$this->_currentAttribute = $attribute;
		return $this;
	}
}