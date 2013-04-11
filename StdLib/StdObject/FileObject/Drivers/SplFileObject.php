<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\FileObject\Drivers;

use WF\StdLib\StdObject\FileObject\FileObjectDriverInterface;
use WF\StdLib\StdObject\StdObjectException;

/**
 * SplFileObject driver for FileObject Standard Library.
 *
 * @package         WF\StdLib\StdObject\FileObject\Drivers
 */
class SplFileObject extends \SplFileObject implements FileObjectDriverInterface
{
	private $_filePath = '';

	/**
	 * @param string $filePath Absolute path to the file.
	 *
	 * @throws \WF\StdLib\StdObject\StdObjectException
	 * @return \WF\StdLib\StdObject\FileObject\FileObjectDriverInterface
	 */
	function __construct($filePath) {
		$this->_filePath = $filePath;
		try {
			parent::__construct($filePath, 'w');
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to construct driver: SplFileObject. ' . $e->getMessage());
		}

		return $this;
	}
}