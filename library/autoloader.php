<?php
use Webiny\Component\ClassLoader\ClassLoader;

/**
 * Register default autoloader before we can do anything else.
 */
require_once 'Webiny/Component/ClassLoader/ClassLoader.php';
ClassLoader::getInstance()->registerMap([
										'Webiny'      => realpath(dirname(__FILE__)) . '/Webiny',
										'Spyc'        => realpath(dirname(__FILE__)) . '/Spyc'
										]);

/**
 * We just need to call the 'getInstance' method which automatically triggers the internal 'init' method
 * due to the implementation of 'SingletonTrait'
 */
\Webiny\WebinyFrameworkBase::getInstance();