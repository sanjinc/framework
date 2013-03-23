<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject;

/**
 * Standard object exception handler
 *
 * @package         WF\StdLib\StdObject
 */

class StdObjectException extends \WF\StdLib\Exception\ExceptionAbstract
{
    function __construct($message) {
        parent::__construct('StdObjectException: ' . $message);
    }
}