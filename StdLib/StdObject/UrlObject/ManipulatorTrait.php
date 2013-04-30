<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\UrlObject;

use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StdObjectManipulatorTrait;
use WF\StdLib\StdObject\StdObjectWrapper;
use WF\StdLib\StdObject\StringObject\StringObject;

/**
 * UrlObject manipulator trait.
 *
 * @package         WF\StdLib\StdObject\UrlObject
 */
trait ManipulatorTrait
{
	use StdObjectManipulatorTrait;

	/**
	 * Set url scheme.
	 *
	 * @param StringObject|string $scheme - Scheme must end with '://'. Example 'http://'.
	 *
	 * @throws StdObjectException
	 * @return $this
	 */
	public function setScheme($scheme) {
		// validate scheme
		try {
			$scheme = new StringObject($scheme);
		} catch (StdObjectException $e) {
			throw new StdObjectException('Invalid $scheme provided. $scheme is not a valid string.', 0, $e);
		}

		if(!$scheme->endsWith('://')) {
			$scheme->val($scheme->val().'://');
		}

		// set the scheme
		$this->_scheme = $scheme->trimRight('://')->val();
		$this->_buildUrl();

		return $this;
	}

	/**
	 * Set url host.
	 *
	 * @param StringObject|string $host Url host.
	 *
	 * @return $this
	 */
	public function setHost($host) {
		$host = new StringObject($host);
		$this->_host = $host->stripTrailingSlash()->trim()->val();

		$this->_buildUrl();

		return $this;
	}

	/**
	 * Set url port.
	 *
	 * @param StringObject|string $port Url port.
	 *
	 * @return $this
	 */
	public function setPort($port) {
		$this->_port = StdObjectWrapper::toString($port);
		$this->_buildUrl();

		return $this;
	}

	/**
	 * Set url path.
	 *
	 * @param StringObject|string $path Url path.
	 *
	 * @return $this
	 */
	public function setPath($path) {
		$path = new StringObject($path);
		$path->trimLeft('/');
		$this->_path = '/'.$path->val();

		$this->_buildUrl();

		return $this;
	}

	/**
	 * Set url query param.
	 *
	 * @param StringObject|ArrayObject|string|array $query Query params.
	 *
	 * @return $this
	 */
	public function setQuery($query) {
		if($this->isStdObject($query)) {
			$query = $query->val();
		}

		$this->_query = $query;
		$this->_buildUrl();

		return $this;
	}

}
