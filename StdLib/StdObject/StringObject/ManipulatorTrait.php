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

use WF\StdLib\StdObject\ArrayObject\ArrayObject;
use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StdObjectManipulatorTrait;
use WF\StdLib\ValidatorTrait;

/**
 * String manipulators.
 *
 * @package         WF\StdLib\StdObject\StringObject
 */

trait ManipulatorTrait
{
	use StdObjectManipulatorTrait;

	abstract function getValue();

	/**
	 * @return $this
	 */
	abstract function getObject();

	/**
	 * Strip whitespace (or other characters) from the beginning and end of a string.
	 *
	 * @param string|null $char  Char you want to trim.
	 *
	 * @return $this
	 */
	public function trim($char = null) {
		if($this->isNull($char)) {
			$value = trim($this->getValue());
		} else {
			$value = trim($this->getValue(), $char);
		}

		$this->updateValue($value);

		return $this;
	}

	/**
	 * Make a string lowercase.
	 *
	 * @return $this
	 */
	public function caseLower() {
		$this->updateValue(strtolower($this->getValue()));

		return $this;
	}

	/**
	 * Make a string uppercase.
	 *
	 * @return $this
	 */
	public function caseUpper() {
		$this->updateValue(strtoupper($this->getValue()));

		return $this;
	}

	/**
	 * Make the first string character upper.
	 *
	 * @return $this
	 */
	public function caseFirstUpper() {
		$this->updateValue(ucfirst($this->getValue()));

		return $this;
	}

	/**
	 * Make the first character of every word upper.
	 *
	 * @return $this
	 */
	public function caseWordUpper() {
		$this->updateValue(ucwords($this->getValue()));

		return $this;
	}

	/**
	 * Make a string's first character lowercase.
	 *
	 * @return $this
	 */
	public function caseFirstLower() {
		$this->updateValue(lcfirst($this->getValue()));

		return $this;
	}

	/**
	 * Inserts HTML line breaks before all newlines in a string.
	 *
	 * @return $this
	 */
	public function nl2br() {
		$this->updateValue(nl2br($this->getValue()));

		return $this;
	}

	/**
	 * Replace HTML line break with newline character.
	 *
	 * @return $this
	 */
	public function br2nl() {
		$search = array(
			'<br>',
			'<br/>',
			'<br />'
		);
		$replace = "\n";

		$this->replace($search, $replace);

		return $this;
	}

	/**
	 * Strips trailing slash from the current string.
	 *
	 * @return $this
	 */
	public function stripTrailingSlash() {
		$this->updateValue(rtrim($this->getValue(), '/'));

		return $this;
	}

	/**
	 * Strips a slash from the start of the string.
	 *
	 * @return $this
	 */
	public function stripStartingSlash() {
		$this->updateValue(ltrim($this->getValue(), '/'));

		return $this;
	}

	/**
	 * Strip the $char from the start of the string.
	 *
	 * @param string $char Char you want to trim.
	 *
	 * @return $this
	 */
	public function trimLeft($char) {
		$this->updateValue(ltrim($this->getValue(), $char));

		return $this;
	}

	/**
	 * Strip the $char from the end of the string.
	 *
	 * @param string $char Char you want to trim.
	 *
	 * @return $this
	 */
	public function trimRight($char) {
		$this->updateValue(rtrim($this->getValue(), $char));

		return $this;
	}

	/**
	 * Returns a substring from the current string.
	 *
	 * @param int $startPosition From which char position will the substring start.
	 * @param int $endPosition   Where will the substring end.
	 *
	 * @throws StdObjectException
	 * @return $this
	 */
	public function subString($startPosition, $endPosition) {
		if(!$this->isNumber($startPosition) || !$this->isNumber($endPosition)) {
			throw new StdObjectException('StringObject: Both $startPosition and $endPosition must be integers.');
		}

		$value = substr($this->getValue(), $startPosition, $endPosition);
		$this->updateValue($value);

		return $this;
	}

	/**
	 * Replaces the occurrences of $search inside the current string with $replace.
	 * This function is CASE-INSENSITIVE.
	 *
	 * @param string|array $search  String, or array of strings, that will replaced.
	 * @param string|array $replace String, or array of strings, with whom $search occurrences will be replaced.
	 * @param null|int     $count   Limit the number of replacements. Default is no limit.
	 *
	 * @throws StdObjectException
	 * @return $this
	 */
	public function replace($search, $replace, $count = null) {
		if(!$this->isNull($count) && !$this->isNumber($count)) {
			throw new StdObjectException('StringObject: $count param must be either null or an integer.');
		}
		$value = str_ireplace($search, $replace, $this->getValue(), $count);
		$this->updateValue($value);

		return $this;
	}

	/**
	 * Explode the current string with the given delimiter and return ArrayObject with the exploded values.
	 *
	 * @param string $delimiter String upon which the current string will be exploded.
	 * @param null   $limit     Limit the number of exploded items.
	 *
	 * @return ArrayObject
	 * @throws StdObjectException
	 */
	public function explode($delimiter, $limit = null) {
		if($this->isNull($limit)) {
			$arr = explode($delimiter, $this->getValue());
		} else {
			$arr = explode($delimiter, $this->getValue(), $limit);
		}

		if(!$arr) {
			throw new StdObjectException('StringObject: Unable to explode the string with the given delimiter "' . $delimiter . '"');
		}

		return new ArrayObject($arr);
	}

	/**
	 * Split the string into chunks.
	 *
	 * @param int $chunkSize Size of each chunk. Set it to 1 if you want to get all the characters from the string.
	 *
	 * @return ArrayObject
	 */
	public function split($chunkSize = 1) {
		$arr = str_split($this->getValue(), $chunkSize);

		return new ArrayObject($arr);
	}

	/**
	 * Generate a hash value from the current string using the defined algorithm.
	 *
	 * @param string $algo        Name of the algorithm used for calculation (md5, sh1, ripemd160,...).
	 *
	 * @throws StdObjectException
	 * @return string
	 */
	public function hash($algo = 'sh1') {
		$algos = new ArrayObject(hash_algos());
		if(!$algos->search($algo)) {
			throw new StdObjectException('StringObject: Invalid hash algorithm provided: "' . $algo . '"'
											 . ' Visit http://www.php.net/manual/en/function.hash-algos.php for more information.');
		}

		$this->updateValue(hash($algo, $this->getValue()));

		return $this;
	}

	/**
	 * Decode html entities in the current string.
	 *
	 * @return $this
	 */
	public function htmlEntityDecode() {
		$this->updateValue(html_entity_decode($this->getValue()));

		return $this;
	}

	/**
	 * Convert all HTML entities to their applicable characters.
	 * For more info visit: http://www.php.net/manual/en/function.htmlentities.php
	 *
	 * @param string $flags    Default flags are set to ENT_COMPAT | ENT_HTML401
	 * @param string $encoding Which encoding will be used in the conversion. Default is UTF-8.
	 *
	 * @return $this
	 */
	public function htmlEntityEncode($flags, $encoding = 'UTF-8') {
		$this->updateValue(htmlentities($this->getValue(), $flags, $encoding));

		return $this;
	}

	/**
	 * Quote string slashes.
	 *
	 * @return $this
	 */
	public function addSlashes() {
		$this->updateValue(addslashes($this->getValue()));

		return $this;
	}

	/**
	 * Un-quote string quoted with StringObject::addSlashes()
	 *
	 * @return $this
	 */
	public function stripSlashes() {
		$this->updateValue(stripslashes($this->getValue()));

		return $this;
	}

	/**
	 * Split the string into chunks with each chunk ending with $endChar.
	 *
	 * @param int    $chunkSize    Size of each chunk.
	 * @param string $endChar      String that will be appended to the end of each chunk.
	 *
	 * @return $this
	 */
	public function chunkSplit($chunkSize = 76, $endChar = "\n") {
		$this->updateValue(chunk_split($this->getValue(), $chunkSize, $endChar));

		return $this;
	}

	/**
	 * Hash current string using md5 algorithm.
	 *
	 * @return $this
	 */
	public function md5() {
		$this->hash('md5');

		return $this;
	}

	/**
	 * Calculates the crc32 polynomial of a string.
	 *
	 * @return $this
	 */
	public function crc32() {
		$this->hash('crc32');

		return $this;
	}

	/**
	 * Calculate the sha1 hash of a string.
	 *
	 * @return $this
	 */
	public function sh1() {
		$this->hash('sh1');

		return $this;
	}

	/**
	 * Parse current string as a query string and return ArrayObject with results.
	 *
	 * @return ArrayObject
	 */
	public function parseString() {
		parse_str($this->getValue(), $arr);

		return new ArrayObject($arr);
	}

	/**
	 * Quote meta characters.
	 * Meta characters are: . \ + * ? [ ^ ] ( $ )
	 *
	 * @return $this
	 */
	public function quoteMeta() {
		$this->updateValue(quotemeta($this->getValue()));

		return $this;
	}

	/**
	 * Format the string according to the provided $format.
	 *
	 * @param string|array $format Format used for string formatting.
	 *                             For more info visit http://www.php.net/manual/en/function.sprintf.php
	 *
	 * @return $this
	 */
	public function format($format) {
		if($this->isArray($format)) {
			$value = vsprintf($this->getValue(), $format);
		} else {
			if($this->isInstanceOf($format, new ArrayObject([]))) {
				$value = vsprintf($this->getValue(), $format->getValue());
			} else {
				$value = sprintf($format, $this->getValue());
			}
		}
		$this->updateValue($value);

		return $this;
	}

	/**
	 * Pad the string to a certain length with another string.
	 *
	 * @param int    $length    Length to which to pad.
	 * @param string $padString String that will be appended.
	 *
	 * @throws StdObjectException
	 * @return $this
	 */
	public function padLeft($length, $padString) {
		if(!$this->isNumber($length)) {
			throw new StdObjectException('StringObject: $length param must be an integer.');
		}
		$this->updateValue(str_pad($this->getValue(), $length, $padString, STR_PAD_LEFT));

		return $this;
	}

	/**
	 * Pad the string to a certain length with another string.
	 *
	 * @param int    $length    Length to which to pad.
	 * @param string $padString String that will be appended.
	 *
	 * @throws StdObjectException
	 * @return $this
	 */
	public function padRight($length, $padString) {
		if(!$this->isNumber($length)) {
			throw new StdObjectException('StringObject: $length param must be an integer.');
		}
		$this->updateValue(str_pad($this->getValue(), $length, $padString, STR_PAD_RIGHT));

		return $this;
	}

	/**
	 * Pad the string to a certain length with another string.
	 *
	 * @param int    $length    Length to which to pad.
	 * @param string $padString String that will be appended.
	 *
	 * @throws StdObjectException
	 * @return $this
	 */
	public function padBoth($length, $padString) {
		if(!$this->isNumber($length)) {
			throw new StdObjectException('StringObject: $length param must be an integer.');
		}
		$this->updateValue(str_pad($this->getValue(), $length, $padString, STR_PAD_BOTH));

		return $this;
	}

	/**
	 * Repeats the current string $multiplier times.
	 *
	 * @param int $multiplier How many times to repeat the string.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function repeat($multiplier) {
		if(!$this->isNumber($multiplier)) {
			throw new StdObjectException('StringObject: $multiplier param must be an integer.');
		}
		$this->updateValue(str_repeat($this->getValue(), $multiplier));

		return $this;
	}

	/**
	 * Shuggle characters in current string.
	 *
	 * @return $this
	 */
	public function shuffle() {
		$this->updateValue(str_shuffle($this->getValue()));

		return $this;
	}

	/**
	 * Remove HTML tags from the string.
	 *
	 * @param string $whiteList A list of allowed HTML tags that you don't want to strip. Example: '<p><a>'
	 *
	 * @return $this
	 */
	public function stripTags($whiteList) {
		$this->updateValue(strip_tags($this->getValue(), $whiteList));

		return $this;
	}

	/**
	 * Reverse the string.
	 *
	 * @return $this
	 */
	public function reverse() {
		$this->updateValue(strrev($this->getValue()));

		return $this;
	}

	/**
	 * Wraps a string to a given number of characters using a string break character.
	 *
	 * @param int    $length The number of characters at which the string will be wrapped.
	 * @param string $break  The line is broken using the optional break parameter.
	 * @param bool   $cut    If the cut is set to TRUE, the string is always wrapped at or before the specified width.
	 *                       So if you have a word that is larger than the given width, it is broken apart.
	 *
	 * @return $this
	 */
	public function wordWrap($length, $break = "\n", $cut = false) {
		$this->updateValue(wordwrap($this->getValue(), $length, $break, $cut));

		return $this;
	}

	/**
	 * Truncate the string to the given length without breaking words.
	 *
	 * @param        $length
	 * @param string $ellipsis
	 *
	 * @return $this
	 */
	public function truncate($length, $ellipsis='') {
		if($this->length()<=$length){
			return $this;
		}

		if($ellipsis!=''){
			$length = $length - strlen($ellipsis);
		}

		$this->wordWrap($length)->subString(0, $this->stringPosition("\n"));

		$this->updateValue($this->getValue().$ellipsis);

		return $this;
	}

	public function cryptEncode($key, $salt = '') {
		/**
		 * @TODO: implement me
		 */
	}

	public function cryptDecode($key, $salt = '') {
		/**
		 * @TODO: implement me
		 */
	}
}