<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject;

use \WF\StdLib\StdObject\StdObjectException;

/**
 * Standard object abstract class.
 * Extend this class when you want to create your own standard object.
 *
 * @package         WF\StdLib\StdObject
 */

abstract class StdObjectAbstract implements StdObjectInterface
{
    /**
     * Throw a standard object exception.
     *
     * @param $message
     *
     * @return StdObjectException
     */
    function exception($message) {
        return new StdObjectException($message);
    }
}