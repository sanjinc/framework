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

use WF\StdLib\StdObject\ArrayObject\ArrayObject;
use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StdObjectManipulatorTrait;

/**
 * String manipulators.
 *
 * @package         WF\StdLib\StdObject\StringObject
 */

trait ManipulatorTrait
{
    use StdObjectManipulatorTrait;

    abstract function getValue();

    /**
     * @return StringObject
     */
    abstract function getObject();

    /**
     * Strip whitespace (or other characters) from the beginning and end of a string.
     *
     * @param string|null $char - char you want to trim
     *
     * @return StringObject
     */
    public function trim($char = null) {
        $value = trim($this->getValue(), $char);
        $this->getObject()->updateValue($value);

        return $this;
    }

    /**
     * Make a string lowercase.
     *
     * @return StringObject
     */
    public function lower() {
        $value = strtolower($this->getValue());
        $this->getObject()->updateValue($value);

        return $this;
    }

    /**
     * Make a string uppercase.
     *
     * @return StringObject
     */
    public function upper() {
        $value = strtoupper($this->getValue());
        $this->getObject()->updateValue($value);

        return $this;
    }

    /**
     * Strips trailing slash from the current string.
     *
     * @return StringObject
     */
    public function stripTrailingSlash() {
        $value = rtrim($this->getValue(), '/');
        $this->getObject()->updateValue($value);

        return $this;
    }

    /**
     * Strips a slash from the start of the string.
     *
     * @return StringObject
     */
    public function stripStartingSlash() {
        $value = ltrim($this->getValue(), '/');
        $this->getObject()->updateValue($value);

        return $this;
    }

    /**
     * Returns a substring from the current string.
     *
     * @param int $startPosition
     * @param int $endPosition
     *
     * @return StringObject
     */
    public function subString($startPosition, $endPosition) {
        $value = substr($this->getValue(), $startPosition, $endPosition);
        $this->getObject()->updateValue($value);

        return $this;
    }

    /**
     * Replaces the $search inside the current value with $replace.
     *
     * @param string|array $search
     * @param string|array $replace
     *
     * @return StringObject
     */
    public function replace($search, $replace) {
        $value = str_replace($search, $replace, $this->getValue());
        $this->getObject()->updateValue($value);

        return $this;
    }

    /**
     * Explode the current string with the given delimiter and return ArrayObject with the exploded values.
     *
     * @param      $delimiter
     * @param null $limit
     *
     * @return ArrayObject
     * @throws StdObjectException
     */
    public function explode($delimiter, $limit = null) {
        if($this->isNull($limit)) {
            $arr = explode($delimiter, $this->getValue());
        } else {
            $arr = explode($delimiter, $this->getValue(), $limit);
        }

        if(!$arr) {
            throw $this->exception('StringObject: Unable to explode the string with the given delimiter "' . $delimiter . '"');
        }

        return new ArrayObject($arr);
    }

    /**
     * Split the string into chunks.
     *
     * @param int $chunkSize
     *
     * @return ArrayObject
     */
    public function split($chunkSize = 1) {
        $arr = str_split($this->getValue(), $chunkSize);

        return new ArrayObject($arr);
    }
}