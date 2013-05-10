<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\StdLib\StdObject\ArrayObject;

use Traversable;
use Webiny\StdLib\StdObject\StdObjectAbstract;
use Webiny\StdLib\StdObject\ArrayObject\ManipulatorTrait;
use Webiny\StdLib\StdObject\ArrayObject\ValidatorTrait;
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;

/**
 * Array standard object.
 *
 * @package         Webiny\StdLib\StdObject\ArrayObject
 */
class ArrayObject extends StdObjectAbstract implements \IteratorAggregate, \ArrayAccess, \Countable
{
    use ManipulatorTrait, ValidatorTrait;

    /**
     * @var array|null Current array.
     */
    protected $_value;

    /**
     * Constructor.
     * Set standard object value.
     *
     * @param null|array|ArrayObject $array
     * @param null|array             $values      - Array of values that will be combined with $array.
     *                                            See http://www.php.net/manual/en/function.array-combine.php for more info.
     *                                  $array param is used as key array.
     *
     * @throws StdObjectException
     */
    public function __construct($array = null, $values = null)
    {
        if (!$this->isArray($array)) {
            if ($this->isNull($array)) {
                $this->_value = array();
            } elseif (StdObjectWrapper::isArrayObject($array)) {
                return $array;
            } else {
                throw new StdObjectException('ArrayObject: Array standard object can only be created from an array.');
            }
        } else {
            if ($this->isInstanceOf($array, $this)) {
                $this->val($array->val());
            } else {
                if ($this->isArray($values)) {
                    // check if both arrays have the same number of values
                    if (count($array) != count($values)) {
                        throw new StdObjectException('ArrayObject: Both arrays must have equal number of items');
                    }
                    $this->_value = array_combine($array, $values);
                } else {
                    $this->_value = $array;
                }
            }
        }
    }

    /**
     * Return the sum of all elements inside the array.
     *
     * @return number
     */
    public function sum()
    {
        return array_sum($this->val());
    }

    /**
     * Return an ArrayObject containing only the keys of current array.
     *
     * @return ArrayObject
     */
    public function keys()
    {
        return new ArrayObject(array_keys($this->val()));
    }

    /**
     * Return an ArrayObject containing only the values of current array.
     *
     * @return ArrayObject
     */
    public function values()
    {
        return new ArrayObject(array_values($this->val()));
    }

    /**
     * Return the last element in the array.
     * If the element is array, ArrayObject is returned, else StringObject is returned.
     *
     * @return StringObject|ArrayObject|StdObjectWrapper
     */
    public function last()
    {
        $arr = $this->val();
        $last = end($arr);

        return StdObjectWrapper::returnStdObject($last);
    }

    /**
     * Returns the first element in the array.
     * If the element is array, ArrayObject is returned, else StringObject is returned.
     *
     * @return StringObject|ArrayObject
     */
    public function first()
    {
        $arr = $this->val();
        $first = reset($arr);

        return StdObjectWrapper::returnStdObject($first);
    }

    /**
     * Returns the number of elements inside the array.
     *
     * @return int
     */
    public function count()
    {
        return count($this->val());
    }

    /**
     * Counts the occurrences of the same array values and groups them into an associate array.
     * NOTE: This function can only count array values that are type of STRING of INTEGER.
     *
     * @throws StdObjectException
     * @return ArrayObject
     */
    public function countValues()
    {
        try {
            /**
             * We must mute errors in this function because it throws a E_WARNING message if array contains something
             * else than STRING or INTEGER.
             */
            @$result = array_count_values($this->val());

            return new ArrayObject($result);
        } catch (\ErrorException $e) {
            throw new StdObjectException('ArrayObject: countValues() can only count STRING and INTEGER values');
        }

    }

    /**
     * To string implementation.
     *
     * @return mixed
     */
    public function __toString()
    {
        return 'Array';
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     * </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     *       The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->keyExists($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     * </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->_value[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     * </p>
     * @param mixed $value  <p>
     *                      The value to set.
     * </p>
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->_value[$offset] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     * </p>
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->_value[$offset]);
    }
}