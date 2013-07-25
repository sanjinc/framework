<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Role;

/**
 * This class holds the name of current role.
 *
 * @package		 Webiny\Component\Security\Authorization
 */
 
class Role{
	private $_role;

	/**
	 * Constructor.
	 *
	 * @param string $role The role name.
	 */
	public function __construct($role)
	{
		$this->_role = (string) $role;
	}

	/**
	 * Returns the name of the role.
	 *
	 * @return string
	 */
	public function getRole()
	{
		return $this->_role;
	}
}