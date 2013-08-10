<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Mailer;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Mailer\MessageInterface;
use Webiny\Component\Mailer\TransportInterface;

/**
 * The mailer interface defines the required methods that every mailer bridge library must implement.
 *
 * @package         Webiny\Bridge\Mailer
 */

interface MailerInterface
{

	/**
	 * Returns an instance of TransportInterface.
	 *
	 * @param ConfigObject $config The configuration of current mailer.
	 *
	 * @return TransportInterface
	 */
	static function getTransport(ConfigObject $config);

	/**
	 * Returns an instance of MessageInterface.
	 *
	 * @param ConfigObject $config The configuration of current mailer
	 *
	 * @return MessageInterface
	 */
	static function getMessage(ConfigObject $config);

}