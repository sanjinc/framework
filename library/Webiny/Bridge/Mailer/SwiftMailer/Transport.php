<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Mailer\SwiftMailer;

use Webiny\Bridge\Mailer\TransportInterface;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Mailer\MessageInterface;

/**
 * Transport class bridges the Mailers' transport layer to SwiftMailer Transport class.
 *
 * @package		 Webiny\Bridge\Mailer\SwiftMailer
 */
 
class Transport implements TransportInterface{

	private $_mailer = null;

	/**
	 * Base constructor.
	 * In the base constructor the bridge gets the mailer configuration.
	 *
	 * @param ConfigObject $config The base configuration.
	 *
	 * @throws SwiftMailerException
	 */
	function __construct($config) {

		$transportType = $config->get('transport.type', 'mail');
		$disableDelivery = $config->get('disable_delivery', false);
		if($disableDelivery){
			$transportType = 'null';
		}

		// create Transport instance
		switch ($transportType) {
			case 'smtp':
				$transport = \Swift_SmtpTransport::newInstance($config->get('transport.host', 'localhost'),
															   $config->get('transport.port', 25),
															   $config->get('transport.auth_mode', null));
				$transport->setUsername($config->get('transport.username', ''));
				$transport->setPassword($config->get('transport.password', ''));
				$transport->setEncryption($config->get('transport.encryption', null));

				break;

			case 'mail':
				$transport = \Swift_MailTransport::newInstance();
				break;

			case 'sendmail':
				$transport = \Swift_SendmailTransport::newInstance($config->get('transport.command',
																				'/usr/sbin/sendmail -bs'));
				break;

			case 'null':
				$transport = \Swift_NullTransport::newInstance();
				break;

			default:
				throw new SwiftMailerException('Invalid transport.type provided.
												Supported types are [smtp, mail, sendmail, null].');
				break;
		}

		// create Mailer instance
		$this->_mailer = \Swift_Mailer::newInstance($transport);

		// register plugins
		$this->_registerPlugins($config);
	}


	/**
	 * Sends the message.
	 *
	 * @param MessageInterface $message  Message you want to send.
	 * @param array|null            $failures To this array failed addresses will be stored.
	 *
	 * @return bool|int Number of success sends, or bool FALSE if sending failed.
	 */
	function send(MessageInterface $message, &$failures = null) {
		return $this->_mailer->send($message, $failures);
	}

	/**
	 * Registers SwiftMailer plugins based on the provided $config.
	 *
	 * @param ConfigObject $config
	 */
	private function _registerPlugins(ConfigObject $config){
		// antiflood
		if($config->get('antiflood', false)){
			$antiflood = new \Swift_Plugins_AntiFloodPlugin($config->get('antiflood.threshold', 99),
															$config->get('antiflood.sleep', 1));
			$this->_mailer->registerPlugin($antiflood);
		}
	}

	/**
	 * Decorators are arrays that contain keys and values. The message body and subject will be scanned for the keys,
	 * and, where found, the key will be replaced with the value.
	 *
	 * @param array $replacements Array [email=> [key1=>value1, key2=>value2], email2=>[...]].
	 *
	 * @return $this
	 */
	function setDecorators(array $replacements) {
		$decoratorPlugin = new \Swift_Plugins_DecoratorPlugin($replacements);
		$this->_mailer->registerPlugin($decoratorPlugin);

		return $this;
	}
}