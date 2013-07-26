<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage\Driver;

use Dropbox\Client;
use Dropbox\Exception;
use Webiny\Bridge\Storage\DriverInterface;

/**
 * Description
 *
 * @package   Webiny\Component\Storage\Driver
 */
class Dropbox implements DriverInterface
{
	protected $client;

	/**
	 * Constructor
	 *
	 * @param Client $client The Dropbox API client
	 */
	public function __construct(Client $client) {
		$this->client = $client;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Dropbox_Exception_Forbidden
	 * @throws \Dropbox_Exception_OverQuota
	 * @throws \OAuthException
	 */
	public function read($key) {
		try {
			$stream = fopen('php://memory', 'rw');
			$metadata = $this->client->getFile($key, $stream);
			rewind($stream);
			$content = stream_get_contents($stream);
			fclose($stream);
			return $content;
		} catch (Exception $e) {
			return false;
		}
	}

	public function getURL($key){
		try {

		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function isDirectory($key) {
		try {
			$metadata = $this->getDropboxMetadata($key);
		} catch (Exception $e) {
			return false;
		}

		return (boolean)isset($metadata['is_dir']) ? $metadata['is_dir'] : false;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Dropbox_Exception
	 */
	public function write($key, $content) {
		$resource = tmpfile();
		fwrite($resource, $content);
		fseek($resource, 0);

		try {
			$this->client->putFile($key, $resource);
		} catch (\Exception $e) {
			fclose($resource);

			throw $e;
		}

		fclose($resource);

		return mb_strlen($content, '8bit');
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($key) {
		try {
			$this->client->delete($key);
		} catch (Exception $e) {
			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function rename($sourceKey, $targetKey) {
		try {
			$this->client->move($sourceKey, $targetKey);
		} catch (Exception $e) {
			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function keys() {
		$metadata = $this->client->getMetaData('/', true);
		die(print_r($metadata));
		if(!isset($metadata['contents'])) {
			return array();
		}

		$keys = array();
		foreach ($metadata['contents'] as $value) {
			$file = ltrim($value['path'], '/');
			$keys[] = $file;
			if('.' !== dirname($file)) {
				$keys[] = dirname($file);
			}
		}
		sort($keys);

		return $keys;
	}

	/**
	 * {@inheritDoc}
	 */
	public function exists($key) {
		try {
			$this->getDropboxMetadata($key);

			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	private function getDropboxMetadata($key) {
		try {
			$metadata = $this->client->getMetaData($key, false);
		} catch (Exception $e) {
			throw new Exception($key, 0, $e);
		}

		// TODO find a way to exclude deleted files
		if(isset($metadata['is_deleted']) && $metadata['is_deleted']) {
			throw new Exception($key);
		}

		return $metadata;
	}

	/**
	 * Returns the last modified time
	 *
	 * @param string $key
	 *
	 * @return integer|boolean An UNIX like timestamp or false
	 */
	public function timeModified($key) {
		try {
			$metadata = $this->getDropboxMetadata($key);
		} catch (Exception $e) {
			return false;
		}

		return strtotime($metadata['modified']);
	}

	/**
	 * Touch a file (change time modified)
	 *
	 * @param $key
	 *
	 * @return mixed
	 */
	public function touch($key) {
		// $key
	}

	/**
	 * @param $key
	 *
	 * @return int
	 */
	public function size($key) {
		// $key
	}
}