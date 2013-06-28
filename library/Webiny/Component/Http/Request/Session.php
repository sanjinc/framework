<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http\Request;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Http\Request\Session\SessionException;
use Webiny\StdLib\StdLibTrait;

/**
 * Session Http component.
 *
 * @package		 Webiny\Component\Http
 */

class Session{
	use StdLibTrait;

	private $_sessionBag;
	private $_sessionId;

	/**
	 * Constructor.
	 *
	 * @param ConfigObject $options Config for components.http.session
	 *
	 * @throws Session\SessionException
	 */
	function __construct(ConfigObject $options){
		// validate that headers have not already been sent
		if(headers_sent()){
			throw new SessionException('Unable to register session handler because headers have already been sent.');
		}

		// remove any shut down functions
		session_register_shutdown();

		// get the driver
		if(isset($options->storage->driver) && $options->storage->driver!='native'){
			$saveHandler = $options->storage->driver;
		}else{
			$saveHandler = '\Webiny\Component\Http\Request\Session\Storage\Native';
		}

		try{
			// try to create driver instance
			$saveHandler = new $saveHandler($options);

			// register driver as session handler
			session_set_save_handler($saveHandler, false);

			// start the session
			session_start();

			// get session id
			$this->_sessionId = session_id();

			// save current session locally
			$this->_sessionBag = $this->arr($_SESSION);
		}catch (\Exception $e){
			throw new SessionException($e->getMessage());
		}
	}

	/**
	 * Get a session value for the given $key.
	 * If key doesn't not exist, $value will be returned and assigned under that key.
	 *
	 * @param string $key   Key for which you wish to get the value.
	 * @param mixed  $value Default value that will be returned if $key doesn't exist.
	 *
	 * @return string Value of the given $key.
	 */
	function get($key, $value=null){
		$return = $this->_sessionBag->key($key, $value, true);
		$_SESSION[$key] = $return;

		return $return;
	}

	/**
	 * Save, or overwrite, a session value under the given $key with the given $value.
	 *
	 * @param string $key   Key for which you wish to get the value.
	 * @param mixed  $value Value that will be stored under the $key.
	 *
	 * @return $this
	 */
	function save($key, $value){
		$this->_sessionBag->removeKey($key)->append($key, $value);
		$_SESSION[$key] = $value;

		return $this;
	}

	/**
	 * Removes the given $key from session.
	 *
	 * @param string $key Name of the session key you wish to remove.
	 *
	 * @return bool
	 */
	function delete($key){
		$this->_sessionBag->removeKey($key);
		unset($_SESSION[$key]);

		return true;
	}

	/**
	 * Get current session id.
	 *
	 * @return string Session id.
	 */
	function getSessionId(){
		return $this->_sessionId;
	}

	/**
	 * Get all session values.
	 *
	 * @return array Key-value array of all session entries.
	 */
	function getAll(){
		return $this->_sessionBag->val();
	}
}