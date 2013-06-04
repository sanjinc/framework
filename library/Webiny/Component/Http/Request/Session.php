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

	function get($key, $value=null){
		$return = $this->_sessionBag->key($key, $value, false);
		$_SESSION[$key] = $return;

		return $return;
	}

	function getSessionId(){
		return $this->_sessionId;
	}

	function getAll(){
		return $this->_sessionBag->val();
	}
}