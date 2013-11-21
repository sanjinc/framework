<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity;

use Webiny\Component\StdLib\SingletonTrait;
use Webiny\Component\StdLib\StdLibTrait;


/**
 * EntityPool class holds instantiated entities
 * EntityTemplate class uses EntityPool to check and retrieve existing entities
 *
 *
 * DEV NOTE: pool should have a 'dirty' flag to keep track of entities that were changed.
 * This is necessary for hierarchical UPDATE queries, when saving parent entity triggers save on child entities.
 *
 * @package Webiny\Component\Entity
 */

class EntityPool
{
	use SingletonTrait, StdLibTrait;

	private $_pool;

	protected function init(){
		$this->_pool = $this->arr();
	}

	/**
	 * Get entity instance or false if entity is not present in the pool
	 * @param $class
	 * @param $id
	 *
	 * @return bool|EntityAbstract
	 */
	public function getEntity($class, $id){
		if($this->_pool->keyExists($class)){
			$entityPool = $this->_pool->key($class);
			if($entityPool->keyExists($id)){
				return $entityPool[$id];
			}
		}
		return false;
	}

}