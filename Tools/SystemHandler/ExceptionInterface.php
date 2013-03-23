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
 * Exception handler interface.
 *
 * @package         WebinyFramework
 * @category        Tools
 * @subcategory        SystemHandler
 */

interface ExceptionInterface
{
    static function exception(Exception $ex);
}