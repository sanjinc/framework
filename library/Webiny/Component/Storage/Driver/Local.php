<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage\Driver;

use Gaufrette\Adapter\Local as GaufretteLocal;
use Webiny\Bridge\Storage\Driver\Local as LocalBridge;
use Webiny\Bridge\Storage\Driver\StorageException;

/**
 * Local storage
 *
 * @package   Webiny\Component\Storage\Driver
 */
class Local extends LocalBridge{

	/**
	 * {@inheritdoc}
	 */
	public function __construct($directory, $publicUrl, $dateFolderStructure = false, $create = false) {
		parent::__construct($directory, $publicUrl, $dateFolderStructure, $create);
	}


}