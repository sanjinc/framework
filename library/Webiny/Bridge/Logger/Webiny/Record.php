<?php

namespace Webiny\Bridge\Logger\Webiny;

/**
 * Logger record container class
 */

class Record
{

	public $name;
	public $message;
	public $level;
	public $context;
	public $datetime;
	public $extra = [];
	public $formatted;
}