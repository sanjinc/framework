<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */
namespace WF\StdLib\StdObject\StringObject;

/**
 * String validator trait.
 *
 * @package         WebinyFramework
 * @category		StdLib
 * @subcategory		String
 */

trait ValidatorTrait
{
	use \WF\StdLib\StdObject\StdObjectValidatorTrait;

	/**
	 * Checks if a string contains the given $char.
	 * If the $char is present, true is returned.
	 *
	 * @param $char
	 * @return bool
	 */
	public function contains($char)
	{
		if(stripos($this->getValue(), $char)!==false)
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the position of the given $string inside the current string object.
	 * Boolean false is returned if the $string is not present inside the current string.
	 * NOTE: Use type validation check in order no to mistake the position '0' (zero) for (bool) false.
	 *
	 * @param string $string
	 * @param int $offset
	 * @return int|bool
	 */
	public function stringPosition($string, $offset=0)
	{
		return stripos($this->getValue(), $string, $offset);
	}

	/**
	 * Checks if the current string starts with the given $string.
	 *
	 * @param string $string
	 * @return bool
	 */
	public function startsWith($string)
	{
		$position = $this->getStringPosition($string);
		if($position!==false && $position==0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Preg matches current string against the given regular expression.
	 *
	 * @param string 	$regEx		Regular expression to match.
	 * @param bool 	$matchAll	Use preg_match_all, or just preg_match. Default is preg_match_all.
	 * @return array|bool	If there are matches, an array with the the $matches is returned, else, false is returned.
	 */
	public function matches($regEx, $matchAll=true)
	{
		preg_match_all('|\[(.*?)\]|', $this->getValue(), $matches);
		if(count($matches)>0)
		{
			return $matches;
		}

		return false;
	}

	/**
	 * Checks if the string length is great than the given length.
	 *
	 * @param int $num
	 * @param bool $inclusive
	 * @return bool
	 */
	public function greaterThan($num, $inclusive=false)
	{
		$length = strlen($this->getValue());
		if($length>$num)
		{
			return true;
		}else if($inclusive && $length>=$num)
		{
			return true;
		}

		return false;
	}
}