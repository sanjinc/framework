<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Mailer;

use Webiny\Component\Mailer\Mailer;

/**
 * Mailer trait.
 *
 * @package         Webiny\Bridge\Mailer
 */

trait MailerTrait
{

	/**
	 * Returns an instance of Mailer.
	 *
	 * @param string $key Key that identifies which mailer configuration to load.
	 *
	 * @return Mailer
	 */
	protected static function mailer($key = 'default') {
		return new Mailer($key);
	}

}