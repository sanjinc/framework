<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Storage\Driver;

use Gaufrette\Adapter\Dropbox as GaufretteDropbox;
use Webiny\Bridge\Storage\DriverInterface;
use Webiny\Bridge\Storage\StorageException;

require_once '../../../Dropbox/src/Dropbox/autoload.php';

/**
 * Local storage
 *
 * @package   Webiny\Bridge\Storage\Driver
 */
class Dropbox extends GaufretteDropbox implements DriverInterface
{

	public function __construct(\Dropbox_API $client) {
		parent::__construct($client);
	}


	/**
	 * Returns the last modified time
	 *
	 * @param string $key
	 *
	 * @return integer|boolean An UNIX like timestamp or false
	 */
	public function timeModified($key) {
		return $this->mtime($key);
	}

	/**
	 * Get file size
	 *
	 * @param $key
	 *
	 * @return int|boolean the size of the file in bytes, or false (and generates an error of level E_WARNING) in case of an error.
	 */
	public function size($key) {

	}

	/**
	 * Touch a file (change time modified)
	 *
	 * @param $key
	 *
	 * @return mixed
	 */
	public function touch($key) {
		return touch($this->computePath($key));
	}

	public function rename($sourceKey, $targetKey) {
		if($this->exists($sourceKey)) {
			return parent::rename($sourceKey, $targetKey);
		}
		throw new StorageException(StorageException::FILE_NOT_FOUND);
	}

	public function read($key) {
		$data = parent::read($key);
		if(!$data){
			throw new StorageException(StorageException::FAILED_TO_READ);
		}
		return $data;
	}

}