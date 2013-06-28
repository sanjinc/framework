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
 * Role Hierarchy class.
 * This class reads the current role hierarchy and creates an array tree of roles for easier access.
 *
 * @package         Webiny\Component\Security\Authorization\Role
 */

class RoleHierarchy
{
	function __construct($hierarchy) {
		$this->_buildRoleMap($hierarchy);
	}

	private function _buildRoleMap($hierarchy){

	}
}