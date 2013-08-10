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
use Webiny\Component\StdLib\FactoryLoaderTrait;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

/**
 * Provides static functions for getting the message instance and transport instance.
 *
 * @package         Webiny\Bridge\Mailer
 */

class Loader
{
	use WebinyTrait, FactoryLoaderTrait, StdLibTrait;

	/**
	 * @var string Default Mailer bridge.
	 */
	static private $_library = '\Webiny\Bridge\Mailer\SwiftMailer\SwiftMailer';

	/**
	 * Returns an instance of MessageInterface based on current bridge.
	 *
	 * @param ConfigObject $config
	 *
	 * @throws MailerException
	 *
	 * @return \Webiny\Component\Mailer\MessageInterface
	 */
	static function getMessage(ConfigObject $config) {
		$lib = self::_getLibrary();

		/** @var MailerInterface $libInstance */
		$libInstance = self::factory($lib, '\Webiny\Bridge\Mailer\MailerInterface');

		$instance = $libInstance::getMessage($config);
		if(!self::isInstanceOf($instance, '\Webiny\Bridge\Mailer\MessageInterface')) {
			throw new MailerException('The message library must implement "\Webiny\Bridge\Mailer\MessageInterface".');
		}

		return $instance;
	}

	/**
	 * Returns an instance of TransportInterface based on current bridge.
	 *
	 * @param ConfigObject $config
	 *
	 * @throws MailerException
	 * @return \Webiny\Component\Mailer\TransportInterface
	 */
	static function getTransport(ConfigObject $config) {
		$lib = self::_getLibrary();

		/** @var MailerInterface $libInstance */
		$libInstance = self::factory($lib, '\Webiny\Bridge\Mailer\MailerInterface');

		$instance = $libInstance::getTransport($config);
		if(!self::isInstanceOf($instance, '\Webiny\Bridge\Mailer\TransportInterface')) {
			throw new MailerException('The message library must implement "\Webiny\Bridge\Mailer\TransportInterface".');
		}

		return $instance;
	}

	/**
	 * Get the name of bridge library which will be used as the driver.
	 *
	 * @return string
	 */
	static function _getLibrary() {
		return self::webiny()->getConfig()->get('bridges.mailer', self::$_library);
	}

	/**
	 * Change the default library used for the driver.
	 *
	 * @param string $pathToClass Path to the new driver class. Must be an instance of \Webiny\Bridge\Cache\CacheInterface
	 */
	static function setLibrary($pathToClass) {
		self::$_library = $pathToClass;
	}

}