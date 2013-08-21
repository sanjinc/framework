<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Mailer\SwiftMailer;

use Webiny\Bridge\Mailer\MailerInterface;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Mailer\MessageInterface;
use Webiny\Component\Mailer\TransportInterface;

require_once dirname(__FILE__).'/../../../../SwiftMailer/lib/swift_init.php';

/**
 * This class is a wrapper for loading Mailer components from the SwiftMailer bridge library.
 *
 * @package         Webiny\Bridge\Mailer\SwiftMailer
 */

class SwiftMailer implements MailerInterface
{

	/**
	 * Returns an instance of TransportInterface.
	 *
	 * @param ConfigObject $config The configuration of current mailer.
	 *
	 * @return TransportInterface
	 */
	static function getTransport(ConfigObject $config) {
		return new Transport($config);
	}

	/**
	 * Returns an instance of MessageInterface.
	 *
	 * @param ConfigObject $config The configuration of current mailer
	 *
	 * @return MessageInterface
	 */
	static function getMessage(ConfigObject $config) {
		$message = new Message();

		$message->setCharset($config->get('character_set', 'utf-8'));
		$message->setMaxLineLength($config->get('max_line_length', 78));

		if($config->get('priority', false)){
			$message->setPriority($config->get('priority', 3));
		}

		if($config->get('sender', false)){
			$message->setSender($config->get('sender.email', 'me@localhost'), $config->get('sender.name', null));
		}

		return $message;
	}
}