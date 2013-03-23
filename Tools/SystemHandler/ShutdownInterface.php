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
 * Shutdown interface
 *
 * @package         WebinyFramework
 * @category        Tools
 * @subcategory        SystemHandler
 */

interface ShutdownInterface
{
    /**
     * Triggered at the end of execution of current request.
     *
     * @return mixed
     */
    static function shutdown();
}