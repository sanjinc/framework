<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Crypt;

/**
 * Crypt trait.
 *
 * @package		 Webiny\Component\Crypt
 */
 
trait CryptTrait{

	private $_wfCryptInstance;

	/**
	 * Get Crypt component instance.
	 *
	 * @return Crypt
	 */
	function crypt(){
		if(!isset($this->_wfCryptInstance)){
			$this->_wfCryptInstance = new Crypt();
		}

		return $this->_wfCryptInstance;
	}
}