<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Config\Drivers;

use Webiny\Component\Config\ConfigException;
use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * Description
 *
 * @package   Webiny\Component\Config\Drivers;
 */

class IniDriver extends DriverAbstract
{
    use ValidatorTrait;

    private $_delimiter = '.';

    /**
     * Set delimiting character for nested properties, ex: a.b.c or a-b-c
     *
     * @param $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->_delimiter = $delimiter;
    }

    /**
     * Parse config resource and build config array
     * @return array
     */
    protected function _buildConfig()
    {
        if (file_exists($this->_resource)) {
            $config = file_get_contents($this->_resource);
        } else {
            $config = $this->_resource;
        }

        return $this->_parseIniString($config);
    }

    /**
     * Validate given config resource and throw ConfigException if it's not valid
     * @throws ConfigException
     */
    protected function _validateResource()
    {
        if (self::isNull($this->_resource)) {
            throw new ConfigException('Config resource can not be NULL! Please provide a valid file path, config string or PHP array.');
        }

        // Perform string checks
        try {
            $this->_resource = new StringObject($this->_resource);
            if ($this->_resource->trim()->length() == 0) {
                throw new ConfigException('Config resource string can not be empty! Please provide a valid file path, config string or PHP array.');
            }
        } catch (StdObjectException $e) {
            throw new ConfigException($e->getMessage());
        }
    }

    /**
     * Parse INI string and return config array
     *
     * @param array $data
     *
     * @return array
     */
    private function _parseIniString($data)
    {
        $config = new ArrayObject();
        $data = parse_ini_string($data, true);
        foreach ($data as $section => $value) {
            $config = $config->mergeRecursive($this->_processValue($section, $value));
        }
        return $config;
    }

    /**
     * Process given section and it's value
     * Config array is empty by default, but it's a nested recursive call, it will be populated with data from previous calls
     *
     * @param string       $section
     * @param string|array $value
     * @param array        $config
     *
     * @return array
     */
    private function _processValue($section, $value, $config = [])
    {
        // Make sure $config is an ArrayObject
        try{
            // Need to catch Exception in case INI string is not properly formed
            $config = new ArrayObject($config);
        } catch(StdObjectException $e){
            $config = new ArrayObject();
        }


        // Create StringObject and trim invalid characters
        $section = new StringObject($section);
        $this->_validateSection($section);

        // Handle nested sections, ex: parent.child.property
        if ($section->contains($this->_delimiter)) {
            /**
             * Explode section and only take 2 elements
             * First element will be the new array key, and second will be passed for recursive processing
             * Ex: parent.child.property will be split into 'parent' and 'child.property'
             */
            $sections = $section->explode($this->_delimiter, 2);
            $sections->removeFirst($section);
            $localConfig = $config->key($section, [], true);
            $config->key($section, $this->_processValue($sections->last()->val(), $value, $localConfig));
        } else {
            // If value is an array, we need to process it's keys
            if ($this->isArray($value)) {
                foreach ($value as $k => $v) {
                    $localConfig = $config->key($section, [], true);
                    $config->key($section, $this->_processValue($k, $v, $localConfig));
                }
            } else {
                $config->key($section, $value);
            }
        }

        return $config->val();
    }

    private function _validateSection(StringObject $section){
        $tmp = $section->explode('.');
        if($tmp->first()->contains('-') || $this->isNumber($tmp->first()->val())) {
            throw new ConfigException(sprintf('Invalid config key "%s"', $section->val()));
        }
    }
}