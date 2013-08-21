<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\OAuth2;

/**
 * This is the OAuth2 user class.
 * This class is returned when you request user details form an OAuth2 server.
 * This class standardizes the data that you get back because every OAuth2 server has its own user structure.
 *
 * @package         Webiny\Component\OAuth2
 */

class OAuth2User
{
	/**
	 * @var string
	 */
	public $username = '';
	/**
	 * @var string
	 */
	public $email = '';
	/**
	 * @var string
	 */
	public $profileUrl = '';
	/**
	 * @var string
	 */
	public $avatarUrl = '';
	/**
	 * @var string
	 */
	public $profileId = '';
	/**
	 * @var string
	 */
	public $firstName = '';
	/**
	 * @var string
	 */
	public $lastName = '';
	/**
	 * @var int
	 */
	public $lastUpdated = '';

	/**
	 * Base constructor.
	 *
	 * @param string $username Users username.
	 * @param string $email Users email.
	 */
	function __construct($username, $email) {
		$this->username = $username;
		$this->email = $email;
	}

	/**
	 * Set the url of users profile on the current OAuth2 server.
	 *
	 * @param string $profileUrl
	 */
	function setProfileUrl($profileUrl) {
		$this->profileUrl = $profileUrl;
	}

	/**
	 * Set the url to users avatar on the current OAuth2 server.
	 *
	 * @param string $avatarUrl
	 */
	function setAvatarUrl($avatarUrl) {
		$this->avatarUrl = $avatarUrl;
	}

	/**
	 * Set the id of user of the current OAuth2 server.
	 *
	 * @param string $id
	 */
	function setProfileId($id) {
		$this->profileId = $id;
	}

	/**
	 * Set users first name.
	 *
	 * @param string $firstName
	 */
	function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	/**
	 * Set users last name.
	 *
	 * @param $lastName
	 */
	function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	/**
	 * Set the date when user last updated his profile on the OAuth2 server.
	 *
	 * @param int $timestamp
	 */
	function setLastUpdateTime($timestamp) {
		$this->lastUpdated = $timestamp;
	}

    // Hack - service name added to the user object
    /**
     * Set the service name that user used to login (like facebook, linkedin etc.)
     *
     * @param string $serviceName
     */
    function setServiceName($serviceName) {
        $this->serviceName = $serviceName;
	}
}