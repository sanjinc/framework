<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\Tools\SystemHandler;

/**
 * Error interface.
 * All registered error callbacks must implement this interface.
 *
 * @package         WebinyFramework
 * @category        Tools
 * @subcategory        SystemHandler
 */

interface ErrorInterface
{
    /**
     * Triggered when an error occurs.
     * @link http://www.php.net/manual/en/function.set-error-handler.php
     *
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     *
     * @return mixed
     */
    static function error($errno, $errstr, $errfile, $errline);
}