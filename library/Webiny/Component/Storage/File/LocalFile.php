<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage\File;

use Webiny\Component\StdLib\StdObject\DateTimeObject\DateTimeObject;
use Webiny\Component\StdLib\StdObjectTrait;

/**
 * LocalFile is used for local disk storage
 *
 * @package   Webiny\Component\Storage
 */

class LocalFile extends File implements TouchableInterface
{
	use StdObjectTrait;

	protected $_size;

	/**
	 * Get file size in bytes
	 *
	 * @return int|boolean Number of bytes or false
	 */
	public function getSize() {
		if($this->_size == null) {
			$this->_size = $this->_storage->getSize($this->_key);
		}

		return $this->_size;
	}

	/**
	 * Get absolute file path
	 *
	 * @return string
	 */
	public function getAbsolutePath(){
		return $this->_storage->getAbsolutePath($this->_key);
	}

	/**
	 * Touch a file (change time modified)
	 *
	 * @return $this
	 */
	public function touch() {
		$this->_storage->touchKey($this->_key);
		$this->_timeModified = null;

		return $this;
	}
}