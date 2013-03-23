<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\StdLib\StdObject\ArrayObject;

use WF\StdLib\StdObject\StdObjectAbstract;
use WF\StdLib\StdObject\ArrayObject\ManipulatorTrait;
use WF\StdLib\StdObject\ArrayObject\ValidatorTrait;

/**
 * Array standard object.
 *
 * @package         WF\StdLib\StdObject\ArrayObject
 */
class ArrayObject extends StdObjectAbstract
{
    use ManipulatorTrait,
        ValidatorTrait;

    private $_array;

    /**
     * Constructor.
     * Set standard object value.
     *
     * @param array $value
     *
     * @throws \WF\StdLib\StdObject\StdObjectException
     */
    public function __construct($value) {
        if(!$this->isArray($value)) {
            if($this->isNull($value)) {
                $this->_array = array();
            } else {
                throw $this->exception('Array standard object can only be created from an array.');
            }
        }

        $this->_array = $value;
    }

    /**
     * Return a value from the array for the given key.
     *
     * @param string $key Array key.
     *
     * @return bool
     * @return mixed
     */
    public function key($key) {
        if(isset($this->getValue()[$key])) {
            return $this->getValue()[$key];
        }

        return false;
    }

    /**
     * Return the last element in the array.
     *
     * @return mixed
     */
    public function last() {
        $arr = $this->getValue();
        return end($arr);
    }

    /**
     * Returns the first element in the array.
     *
     * @return mixed
     */
    public function first() {
        return reset($this->getValue());
    }

    /**
     * Return current standard objects value.
     *
     * @return array
     */
    public function getValue() {
        return $this->_array;
    }

    /**
     * Returns the current standard object instance.
     *
     * @return ArrayObject
     */
    public function getObject() {
        return $this;
    }

    /**
     * To string implementation.
     *
     * @return mixed
     */
    public function __toString() {
        return 'Array';
    }

    /**
     * The update value method is called after each modifier method.
     * It updates the current value of the standard object.
     */
    function updateValue(&$value) {
        $this->_array = $value;
    }
}