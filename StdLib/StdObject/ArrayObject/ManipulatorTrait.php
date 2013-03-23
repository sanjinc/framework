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

use WF\StdLib\StdObject\StdObjectManipulatorTrait;
use WF\StdLib\StdObject\StringObject\StringObject;

/**
 * Manipulator methods for array standard object.
 *
 * @package         WF\StdLib\StdObject\ArrayObject
 */
trait ManipulatorTrait
{
    use StdObjectManipulatorTrait;

    abstract function getValue();

    /**
     * @return ArrayObject
     */
    abstract function getObject();

    /**
     * Inserts an element to the end of the array.
     * If you set both params, that first param is the key, and second is the value, else first param is the value, and the second is ignored.
     *
     * @param mixed $k
     * @param mixed $v
     *
     * @return $this
     */
    public function append($k, $v = null) {
        $array = $this->getValue();

        if(!$this->isNull($v)) {
            $array[$k] = $v;
        } else {
            $array[] = $k;
        }

        $this->updateValue($array);

        return $this;
    }

    /**
     * Inserts an element at the beginning of the array.
     * If you set both params, that first param is the key, and second is the value, else first param is the value, and the second is ignored.
     *
     * @param mixed $k
     * @param mixed $v
     *
     * @return $this
     */
    public function prepend($k, $v = null) {
        $array = $this->getValue();

        if(!$this->isNull($v)) {
            $array = [
                $k,
                $v
            ] + $array;
        } else {
            $array = $k + $array;
        }

        $this->updateValue($array);

        return $this;
    }

    /**
     * Removes the first element from the array.
     *
     * @return $this
     */
    public function removeFirst() {
        array_shift($this->getValue());

        return $this;
    }

    /**
     * Removes the last element from the array.
     *
     * @return $this
     */
    public function removeLast() {
        array_pop($this->getValue());

        return $this;
    }

    /**
     * Remove the element from the array under the given $key.
     *
     * @param $key
     *
     * @return $this
     */
    public function removeKey($key) {
        if($this->key($key)) {
            unset($this->getValue()[$key]);
        }

        return $this;
    }

    /**
     * Implode the array with the given $glue.
     *
     * @param $glue
     *
     * @return StringObject
     */
    public function implode($glue) {
        return new StringObject(implode($glue, $this->getValue()));
    }
}