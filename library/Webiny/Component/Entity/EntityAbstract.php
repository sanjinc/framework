<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity;

use Webiny\Component\Entity\Attribute\AttributeAbstract;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\ArrayObject\ArrayObject;


/**
 * Entity
 * @package \Webiny\Component\Entity
 */

abstract class EntityAbstract
{
	use StdLibTrait;

	/**
	 * Entity attributes
	 * @var ArrayObject
	 */
	protected $_attributes;

	/**
	 * Primary key attribute
	 * @var string
	 */
	protected $_entityPrimaryKey = 'id';

	/**
	 * View mask (used for grids and many2one input fields)
	 * @var string
	 */
	protected $_entityMask = '{id}';

	/**
	 * Entity constructor
	 */
	public function __construct(){
		$this->_attributes = $this->arr();
		$this->_entityStructure();
	}

	/**
	 * This method is called during instantiation to build entity structure
	 * @return void
	 */
	protected abstract function _entityStructure();

	/**
	 * Get entity attribute
	 *
	 * @param string $attribute
	 *
	 * @return AttributeAbstract
	 */
	public function getAttribute($attribute){
		return $this->_attributes[$attribute];
	}

	/**
	 * Get all entity attributes
	 *
	 * @return ArrayObject
	 */
	public function getAttributes(){
		return $this->_attributes;
	}

	/**
	 * Save entity attributes to database
	 */
	public function save() {
		// Check if primary key is empty and INSERT / UPDATE depending on that
	}

	/**
	 * Delete entity
	 */
	public function delete() {
		// Delete current entity, and related entities following referential integrity rules
	}

	/**
	 * Populate entity with given data
	 * @param $data
	 *
	 * @throws EntityException
	 * @return $this
	 */
	public function populate($data){
		$validation = $this->arr();
		/** @var $object AttributeAbstract */
		foreach($this->_attributes as $attributeName => $object){
			if(isset($data[$object->attr()])){
				$dataValue = $data[$object->attr()];
				try{
					$object->validate($dataValue)->value($dataValue);
				} catch(ValidationException $e){
					$validation[$attributeName] = $e;
				}
			}
		}

		if($validation->count() > 0){
			$ex = new EntityException(EntityException::VALIDATION_FAILED, [$validation->count()]);
			$ex->setInvalidAttributes($validation);
			throw $ex;
		}

		return $this;
	}

	/**
	 * Find entities
	 * - single value assumes it's a primary key: find(12)
	 * - array will build a filter using given attributes
	 *
	 * @param mixed $conditions
	 * @param int   $limit
	 * @param int   $page
	 *
	 * @return boolean|array|EntityAbstract
	 */
	public static function find($conditions, $limit = 0, $page = 0) {
		return self::_findEntities($conditions, $limit, $page);
	}

	/**
	 * Set primary key attribute
	 * @param string $attribute
	 */
	protected function _entityPrimaryKey($attribute){
		$this->_entityPrimaryKey = $attribute;
	}

	/**
	 * Set entity mask, ex: '{name} ({id})'
	 * @param $mask
	 */
	protected function _entityMask($mask){
		$this->_entityMask = $mask;
	}

	/**
	 * Main method for entity lookup
	 */
	protected static function _findEntities($conditions = [], $limit = 0, $page = 0) {
		return [];
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