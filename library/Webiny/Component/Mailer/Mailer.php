<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Mailer;

use Webiny\Bridge\Mailer\Loader;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

/**
 * This is the Mailer component class.
 *
 * This class provides access to mail Transport and mail Message object.
 * Use the getMessage to create an email message, and then use the send method to send it using the Transport object.
 *
 * @package         Webiny\Component\Mailer
 */

class Mailer
{
	use WebinyTrait, StdLibTrait;

	/**
	 * @var TransportInterface
	 */
	private $_transport;

	/**
	 * @var mixed|\Webiny\Component\Config\ConfigObject
	 */
	private $_config;

	/**
	 * Base constructor.
	 *
	 * @param string $mailer Key of the mailer configuration.
	 *
	 * @throws MailerException
	 */
	function __construct($mailer = 'default') {
		$config = $this->webiny()->getConfig()->get('components.mailer.' . $mailer, false);
		if(!$config) {
			throw new MailerException('Unable to load the configuration for "' . $mailer . '" mailer.');
		}

		$this->_config = $config;
		$this->_transport = Loader::getTransport($config);
	}

	/**
	 * Creates a new message.
	 *
	 * @return MessageInterface
	 */
	function getMessage() {
		return Loader::getMessage($this->_config);
	}

	/**
	 * Sends the message.
	 *
	 * @param MessageInterface $message  Message you want to send.
	 * @param null|array       $failures To this array failed addresses will be stored.
	 *
	 * @return bool|int Number of success sends, or bool FALSE if sending failed.
	 */
	function send(MessageInterface $message, &$failures = null) {
		return $this->_transport->send($message, $failures);
	}

	/**
	 * Decorators are arrays that contain keys and values. The message body and subject will be scanned for the keys,
	 * and, where found, the key will be replaced with the value.
	 *
	 * @param array  $replacements Array [key1=>value1, key2=>value2].
	 *
	 * @return $this
	 */
	function setDecorators(array $replacements) {
		$this->_transport->setDecorators($replacements);

		return $this;
	}
}