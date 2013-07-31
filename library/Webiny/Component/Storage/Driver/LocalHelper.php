<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage\Driver;

use Webiny\Component\Storage\StorageException;
use Webiny\StdLib\SingletonTrait;

/**
 * Local storage driver helper
 *
 * @package   Webiny\Component\Storage\Driver;
 *
 * The class is taken from KnpLabs-Gaufrette library and is adapted to suite WebinyFramework
 * Original author: Antoine Hérault <antoine.herault@gmail.com>
 */

class LocalHelper
{
	use SingletonTrait;

	/**
	 * Build absolute path by given $key and $directory
	 *
	 * @param $key
	 * @param $directory
	 * @param $create
	 *
	 * @return mixed
	 */
	public function buildPath($key, $directory, $create) {
		$this->ensureDirectoryExists($directory, $create);

		return $this->normalizeDirectoryPath($directory . '/' . $key);
	}

	/**
	 * Normalizes the given path
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public function normalizePath($path) {
		$path = str_replace('\\', '/', $path);
		$prefix = $this->getAbsolutePrefix($path);
		$path = substr($path, strlen($prefix));
		$parts = array_filter(explode('/', $path), 'strlen');
		$tokens = array();

		foreach ($parts as $part) {
			switch ($part) {
				case '.':
					continue;
				case '..':
					if(0 !== count($tokens)) {
						array_pop($tokens);
						continue;
					} elseif(!empty($prefix)) {
						continue;
					}
				default:
					$tokens[] = $part;
			}
		}

		return $prefix . implode('/', $tokens);
	}

	/**
	 * Indicates whether the given path is absolute or not
	 *
	 * @param string $path A normalized path
	 *
	 * @return boolean
	 */
	public function isAbsolute($path) {
		return '' !== $this->getAbsolutePrefix($path);
	}

	/**
	 * Returns the absolute prefix of the given path
	 *
	 * @param string $path A normalized path
	 *
	 * @return string
	 */
	public function getAbsolutePrefix($path) {
		preg_match('|^(?P<prefix>([a-zA-Z]:)?/)|', $path, $matches);

		if(empty($matches['prefix'])) {
			return '';
		}

		return strtolower($matches['prefix']);
	}

	/**
	 * Make sure the target directory exists
	 *
	 * @param      $directory
	 * @param bool $create
	 *
	 * @throws \Webiny\Component\Storage\StorageException
	 */
	public function ensureDirectoryExists($directory, $create = false) {
		if(!is_dir($directory)) {
			if(!$create) {
				throw new StorageException(StorageException::STORAGE_DIRECTORY_DOES_NOT_EXIST, [$directory]);
			}
			$this->_createDirectory($directory);
		}
	}

	/**
	 * Normalize path (strip '.', '..' and make sure it's not a symlink)
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public function normalizeDirectoryPath($path) {
		$path = $this->normalizePath($path);

		if(is_link($path)) {
			$path = realpath($path);
		}

		return $path;
	}

	protected function _createDirectory($directory) {
		$umask = umask(0);
		$created = mkdir($directory, 0777, true);
		umask($umask);

		if(!$created) {
			throw new StorageException(StorageException::STORAGE_DIRECTORY_COULD_NOT_BE_CREATED, [$directory]);
		}
	}
}