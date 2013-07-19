<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authorization;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Http\HttpTrait;
use Webiny\Component\Security\Authorization\Voters\AuthenticationVoter;
use Webiny\Component\Security\Authorization\Voters\RoleVoter;
use Webiny\Component\Security\Authorization\Voters\RoleVoterInterface;
use Webiny\Component\Security\Role\Role;
use Webiny\Component\Security\Role\RoleHierarchy;
use Webiny\Component\Security\User\UserAbstract;
use Webiny\StdLib\StdLibTrait;

/**
 * Description
 *
 * @package         Webiny\Component\Security\Authorization
 */

class AccessControl
{

	use StdLibTrait, HttpTrait;

	// 1 single voter denies access
	const VOTER_STR_UNANIMOUS = 'unanimous';

	// 1 single voter grants access
	const VOTER_STR_AFFIRMATIVE = 'affirmative';

	// Majority wins
	const VOTER_STR_CONSENSUS = 'consensus';

	private $_config;
	private $_strategy;
	protected $_currentPath;

	/**
	 * Base constructor.
	 *
	 * @param UserAbstract $user   Instance of current user.
	 * @param ConfigObject $config Access control configuration.
	 */
	function __construct(UserAbstract $user, ConfigObject $config) {
		$this->_config = $config;
		$this->_currentPath = $this->str($this->request()->getCurrentUrl(true)->getPath());
		$this->_user = $user;
		$this->_setDecisionStrategy();
	}

	/**
	 * Checks if current user is allowed access.
	 *
	 * @return bool
	 */
	function isUserAllowedAccess() {
		$requestedRoles = $this->_getRequestedRoles();

		// we allow access if there are no requested roles that the user must have
		if(count($requestedRoles)<1){
			return true;
		}

		return $this->_getAccessDecision($requestedRoles);
	}

	/**
	 * Creates an array of registered Voters.
	 *
	 * @return array Array of registered voters.
	 */
	private function _getVoters() {
		// we have 2 built in voters
		// @TODO: Read the registered voter services by 'tag' property once it's implemented
		$voters = [];

		$voters[] = new AuthenticationVoter();
		$voters[] = new RoleVoter();

		return $voters;
	}

	/**
	 * Returns an array of roles required by the access rule.
	 *
	 * @return array
	 */
	private function _getRequestedRoles() {
		$rules = $this->_config->get('rules', false);
		if(!$rules) {
			return [];
		}

		// see which of the rules matches the path and extract the requested roles for access
		foreach ($rules as $r) {
			$path = $r->get('path', false);
			if($path && $this->_testPath($path)) {
				$roles = $r->get('roles', []);
				if($this->isString($roles)) {
					$roles = (array)$roles;
				} else {
					$roles = $roles->toArray();
				}

				// covert the role names to Role instances
				foreach ($roles as &$r) {
					$r = new Role($r);
				}

				return $roles;
			}
		}

		return [];
	}

	/**
	 * Tests the given $path if it's within the current request path.
	 *
	 * @param string $path Path against to whom to test.
	 *
	 * @return bool True if path is within the current request, otherwise false.
	 */
	private function _testPath($path) {
		$result = $this->_currentPath->match($path);

		return ($result) ? true : false;
	}

	/**
	 * Sets the decision strategy based on the application configuration.
	 *
	 * @throws AccessControlException
	 */
	private function _setDecisionStrategy() {
		$strategy = $this->_config->get('decision_stategy', 'unanimous');
		if($strategy != self::VOTER_STR_AFFIRMATIVE
			&& $strategy != self::VOTER_STR_CONSENSUS
			&& $strategy != self::VOTER_STR_UNANIMOUS
		) {

			throw new AccessControlException('Invalid access control decision strategy "' . $strategy . '"');
		}

		$this->_strategy = $strategy;
	}

	/**
	 * This method get the votes from all the voters and sends them to the ruling.
	 * The result of ruling is then returned.
	 *
	 * @param array        $requestedRoles An array of requested roles for the current access map.
	 *
	 * @return bool True if access is allowed to the current user, otherwise false.
	 */
	private function _getAccessDecision(array $requestedRoles) {
		$voters = $this->_getVoters();
		$userClassName = get_class($this->_user);

		$voteScore = 0;
		$maxScore = 0;
		foreach ($voters as $v) {
			/**
			 * @var $v RoleVoterInterface
			 */
			if($v->supportsUserClass($userClassName)) {
				$maxScore++;
				$voteScore += $v->vote($this->_user, $requestedRoles);
			}
		}

		return $this->_whatsTheRuling($voteScore, $maxScore);
	}

	/**
	 * Method that decides if access is allowed or not based on the results of votes and the defined decision strategy.
	 *
	 * @param int $votes    The voting score.
	 * @param int $maxVotes Max possible number of votes.
	 *
	 * @return bool True if access is allowed, otherwise false.
	 */
	private function _whatsTheRuling($votes, $maxVotes) {
		switch ($this->_strategy) {
			case self::VOTER_STR_UNANIMOUS:
				return ($votes == $maxVotes);
				break;

			case self::VOTER_STR_CONSENSUS:
				return ($votes > ($maxVotes - $votes));
				break;

			case self::VOTER_STR_AFFIRMATIVE:
				return ($votes > 0);
				break;
		}
	}
}