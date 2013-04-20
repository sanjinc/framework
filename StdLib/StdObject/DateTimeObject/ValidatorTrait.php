<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\DateTimeObject;

/**
 * DateObject validator
 *
 * @package		 WF\StdLib\StdObject\DateObject
 */
 
trait ValidatorTrait{

	use \WF\StdLib\ValidatorTrait;

	/**
	 * Check if current DateTimeObject is a leap year.
	 *
	 * @return string
	 */
	function isLeap(){
		return date('L', $this->getTimestamp());
	}

	/**
	 * Check if current DateTimeObject is in future.
	 *
	 * @return bool
	 */
	function isFuture(){
		if($this->getTimestamp()>time()){
			return true;
		}

		return false;
	}

	/**
	 * Check if current DateTimeObject is in past.
	 *
	 * @return bool
	 */
	function isPast(){
		if($this->isFuture()){
			return false;
		}

		return true;
	}

	/**
	 * Check if $time is larger then current DateTimeObject.
	 *
	 * @param int|string|\DateTime|DateTimeObject $time     Date to compare to.
	 *
	 * @return bool
	 */
	function largerThan($time){
		$diff = $this->diff($time, false);
		if($diff->key('invert')>0){
			return false;
		}

		return true;
	}

	/**
	 * Check if $time is smaller then current DateTimeObject.
	 *
	 * @param int|string|\DateTime|DateTimeObject $time     Date to compare to.
	 *
	 * @return bool
	 */
	function smallerThan($time){
		if($this->largerThan($time)){
			return false;
		}

		return true;
	}
}