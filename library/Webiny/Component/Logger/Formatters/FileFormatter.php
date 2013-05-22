<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Logger\Formatters;
use Webiny\Bridge\Logger\Webiny\FormatterAbstract;
use Webiny\Bridge\Logger\Webiny\Record;
use Webiny\StdLib\ValidatorTrait;


/**
 * Formats incoming records into a one-line string
 *
 * @package         Webiny\Component\Logger\Formatters
 */
class FileFormatter extends FormatterAbstract
{
	use ValidatorTrait;

    const SIMPLE_FORMAT = "[%datetime%] %name%.%level%: %message% %context% %extra%\n";
    const DATE_FORMAT = "Y-m-d H:i:s";

    protected $format;

    /**
     * @param string $format     The format of the message
     * @param string $dateFormat The format of the timestamp: one supported by DateTime::format
     */
    public function __construct($format = null, $dateFormat = null)
    {
        $this->format = $format ?: static::SIMPLE_FORMAT;
		$this->dateFormat = $dateFormat ?: static::DATE_FORMAT;
    }

	public function formatRecord(Record $record)
    {
		
        $output = $this->format;
        foreach ($record->extra as $var => $val) {
            if (false !== strpos($output, '%extra.'.$var.'%')) {
                $output = str_replace('%extra.'.$var.'%', $val, $output);
                unset($record->extra[$var]);
            }
        }

        foreach ($record as $var => $val) {
			if($this->isDateTimeObject($val)){
				$val = $val->format($this->dateFormat)->val();
			}
            $output = str_replace('%'.$var.'%', $val, $output);
        }

		die($output);

        return $output;
    }

    public function formatRecords(array $records)
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->formatRecord($record);
        }

        return $message;
    }
}
