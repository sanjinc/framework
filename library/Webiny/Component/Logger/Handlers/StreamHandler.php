<?php

namespace Webiny\Component\Logger\Handlers;

use Webiny\Bridge\Logger\LoggerHandlerAbstract;

class StreamHandler extends LoggerHandlerAbstract
{

	protected $stream;
	protected $url;

	/**
	 * @param string $stream
	 * @param array  $levels
	 * @param bool   $bubble
	 */
	public function __construct($stream, $levels = [], $bubble = true) {
		parent::__construct($levels, $bubble);
		if(is_resource($stream)) {
			$this->stream = $stream;
		} else {
			$this->url = $stream;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function stopLogging() {
		if(is_resource($this->stream)) {
			fclose($this->stream);
		}
		$this->stream = null;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function write(array $record) {
		if(null === $this->stream) {
			if(!$this->url) {
				throw new \LogicException('Missing stream url, the stream can not be opened. This may be caused by a premature call to close().');
			}
			$errorMessage = null;
			set_error_handler(function ($code, $msg) use (&$errorMessage) {
				$errorMessage = preg_replace('{^fopen\(.*?\): }', '', $msg);
			});
			$this->stream = fopen($this->url, 'a');
			restore_error_handler();
			if(!is_resource($this->stream)) {
				$this->stream = null;
				throw new \UnexpectedValueException(sprintf('The stream or file "%s" could not be opened: ' . $errorMessage,
															$this->url));
			}
		}
		fwrite($this->stream, (string)$record['formatted']);
	}
}
