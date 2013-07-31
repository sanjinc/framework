<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage\Directory;

use Traversable;
use Webiny\Component\Storage\File\LocalFile;
use Webiny\Component\Storage\Storage;
use Webiny\StdLib\StdLibTrait;


/**
 * Directory class used with storage component
 *
 * @package Webiny\Component\Storage\Directory
 */

class Directory implements DirectoryInterface, \IteratorAggregate
{
	use StdLibTrait;

	protected $_key;
	protected $_storage;
	protected $_recursive;
	protected $_items;
	protected $_regex;

	/**
	 * Constructor
	 *
	 * @param string      $key           File key
	 * @param Storage     $storage       Storage to use
	 * @param bool        $recursive     (Optional) By default, Directory will only read the first level if items.
	 *                                   If set to false, Directory will read all children items and list them as one-dimensional array.
	 * @param null|string $filter        (Optional) Filter to use when reading directory items
	 */
	public function __construct($key, Storage $storage, $recursive = false, $filter = null) {
		$this->_key = $key;
		$this->_recursive = $recursive;
		$this->_storage = $storage;
		$this->_parseFilter($filter);
	}

	/**
	 * Filter items in a directory using given regex or extension.
	 *
	 * Example 1: '*.pdf' ($condition starting with * means: anything that ends with)
	 *
	 * Example 2: 'file*' ($condition ending with * means: anything that starts with)
	 *
	 * Example 3: Any file that ends with `file.zip`: '/(\S+)?file.zip/'
	 *
	 * @param string $condition
	 *
	 * @return Directory
	 */
	public function filter($condition) {
		return new static($this->_key, $this->_storage, $condition);
	}

	/**
	 * Count number of items in a directory
	 *
	 * @return int
	 */
	public function count() {
		$this->_loadItems();
		return count($this->_items);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 */
	public function getIterator() {
		$this->_loadItems();
		return new \ArrayIterator($this->_items);
	}

	/**
	 * Get Storage used by the DirectoryInterface instance
	 *
	 * @return Storage
	 */
	public function getStorage() {
		return $this->_storage;
	}

	/**
	 * Get directory key
	 *
	 * @return string Directory key
	 */
	public function getKey() {
		return $this->_key;
	}

	protected function _parseFilter($filter) {
		if(empty($filter)) {
			return;
		}
		$filter = $this->str($filter);
		if($filter->startsWith('*')) {
			$filter->replace('.', '\.');
			$this->_regex = '/(\S+)' . $filter . '/';
		} elseif($filter->endsWith('*')) {
			$filter->replace('.', '\.');
			$this->_regex = '/' . $filter . '(\S+)/';
		} else {
			$this->_regex = $filter;
		}
	}

	protected function _loadItems(){
		if($this->_items == null){
			$keys = $this->_storage->getKeys($this->_key, $this->_recursive);

			// Filter keys if regex is set
			if($this->_regex) {
				foreach ($keys as $k => $v) {
					if(!preg_match($this->_regex, $v)) {
						unset($keys[$k]);
					}
				}
			}
			// Instantiate files/directories
			$this->_items = [];
			foreach($keys as $key){
				if($this->_storage->isDirectory($key)){
					$this->_items[$key] = new Directory($key, $this->_storage);
				} else {
					$this->_items[$key] = new LocalFile($key, $this->_storage);
				}
			}
		}
	}
}