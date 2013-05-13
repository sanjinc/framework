<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Yaml;

use Webiny\StdLib\Exception\ExceptionAbstract;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\FileObject\FileObject;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;

/**
 * Abstract bridge for Yaml
 *
 * @package   Webiny\Bridge\Yaml
 */
abstract class YamlAbstract implements YamlInterface
{
	use StdLibTrait;

	protected $_resource = null;

	public function setResource($resource) {
		$this->_resource = $resource;
		return $this;
	}
}