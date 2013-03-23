<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\StdLib\StdObject\StringObject;

use WF\StdLib\StdObject\StdObjectAbstract;
use WF\StdLib\StdObject\StringObject\ManipulatorTrait;
use WF\StdLib\StdObject\StringObject\ValidatorTrait;

/**
 * String standard object.
 *
 * @package         WF\StdLib\StdObject\StringObject
 */

class StringObject extends StdObjectAbstract
{
    use ManipulatorTrait,
        ValidatorTrait;

    /**
     * @var string
     */
    protected $_wfString;


    /**
     * Constructor.
     * Set standard object value.
     *
     * @param mixed $value
     */
    public function __construct($value) {
        $this->_wfString = (string)$value;
    }

    /**
     * Returns the lenght of the current string.
     *
     * @return int
     */
    public function length() {
        return strlen($this->getValue());
    }

    /**
     * Return current standard objects value.
     *
     * @return string
     */
    public function getValue() {
        return $this->_wfString;
    }

    /**
     * To string implementation.
     *
     * @return string
     */
    public function __toString() {
        return $this->getValue();
    }

    /**
     * Returns the current standard object instance.
     * @return $this
     */
    public function getObject() {
        return $this;
    }

    /**
     * The update value method is called after each modifier method.
     * It updates the current value of the standard object.
     */
    public function updateValue(&$value) {
        $this->_wfString = $value;
    }

}