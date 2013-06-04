<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http\Request\Session;

use Webiny\Component\Config\ConfigObject;

/**
 * Description
 *
 * @package		 Webiny\
 */
 
interface SessionStorageInterface extends \SessionHandlerInterface{

	/**
	 * Constructor.
	 *
	 * @param ConfigObject $config Session config.
	 */
	function __construct(ConfigObject $config);
}