<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */
namespace WF\StdLib;

use WF\StdLib\Exception\Exception;
use WF\StdLib\Exception\ExceptionInterface;

/**
 * Trait for issuing Exceptions.
 *
 * @package    WF\StdLib
 */
trait ExceptionTrait
{

    /**
     * Throw an exception with the given $message.
     * If exception $type is defined, the exception will be thrown using that type.
     *
     * @param string             $message
     * @param ExceptionInterface $type
     *
     * @throws Exception
     * @throws \Exception
     */
    public function exception($message, Exception\ExceptionInterface $type = null) {
        if(self::isNull($type)) {
            throw new Exception\Exception($message);
        } else {
            throw new $type($message);
        }
    }
}