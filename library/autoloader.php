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

\Webiny\WebinyFramework::init();