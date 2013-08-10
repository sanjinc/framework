<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Mailer\SwiftMailer;

use Webiny\Bridge\Mailer\MessageInterface;
use Webiny\Component\StdLib\StdLibTrait;

/**
 * Bridge to SwiftMailer Message class.
 *
 * @package         Webiny\Bridge\Mailer\SwiftMailer
 */

class Message extends \Swift_Message implements MessageInterface
{
	use StdLibTrait;

	/**
	 * Attach a file to your message.
	 *
	 * @param string      $pathToFile  Absolute path to the file.
	 * @param string      $fileName    Optional name that will be set for the attachment.
	 * @param string|null $contentType Attachment header content type.
	 *
	 * @return $this
	 */
	function addAttachment($pathToFile, $fileName = null, $contentType = null) {
		$attachment = new \Swift_Attachment($pathToFile, $fileName, $contentType);
		$this->attach($attachment);

		return $this;
	}

	/**
	 * Specifies the encoding scheme in the message.
	 *
	 * @param string $encoding
	 *
	 * @return $this
	 * @throws SwiftMailerException
	 */
	function setContentTransferEncoding($encoding) {

		switch ($encoding){
			case '7bit':
				$encoder = \Swift_Encoding::get7BitEncoding();
				break;

			case '8bit':
				$encoder = \Swift_Encoding::get8BitEncoding();
				break;

			case 'base64':
				$encoder = \Swift_Encoding::getBase64Encoding();
				break;

			case 'qp':
				$encoder = \Swift_Encoding::getQpEncoding();
				break;

			default:
				throw new SwiftMailerException('Invalid encoding name provided.
												Valid encodings are [7bit, 8bit, base64, qp].');
				break;
		}

		$this->setEncoder($encoder);

		return $this;
	}

	/**
	 * Get the defined encoding scheme.
	 *
	 * @return string
	 */
	function getContentTransferEncoding() {
		return $this->getEncoder();
	}

	/**
	 * Adds a header to the message.
	 *
	 * @param string     $name   Header name.
	 * @param string     $value  Header value.
	 * @param null|array $params Optional array of parameters.
	 *
	 * @return $this
	 */
	function addHeader($name, $value, $params = null) {
		if(is_array($params)){
			$this->getHeaders()->addParameterizedHeader($name, $value, $params);
		}else{
			$this->getHeaders()->addTextHeader($name, $value);
		}

		return $this;
	}

	/**
	 * Uses a template file as body content.
	 *
	 * @param string $pathToTemplate Absolute path to the template.
	 *
	 * @return $this
	 */
	function setBodyFromTemplate($pathToTemplate) {
		$file = $this->file($pathToTemplate);
		$this->setBody($file->getFileContent());

		return $this;
	}
}