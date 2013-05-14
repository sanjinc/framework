<?php
error_reporting(E_ALL);
ini_set('display_erors', 1);

use Webiny\Component\ClassLoader\ClassLoader;

/**
 * Register default autoloader before we can do anything else.
 */
require_once 'library/Webiny/Component/ClassLoader/ClassLoader.php';
ClassLoader::getInstance()->registerMap([
										// a namespace rule
										'Webiny'      => realpath(dirname(__FILE__)) . '/library/Webiny',
										'Jamm\Memory' => realpath(dirname(__FILE__)) . '/library/Memory',
										'Spyc'        => realpath(dirname(__FILE__)) . '/library/Spyc',
										// a PEAR rule
										'Swift_'      => realpath(dirname(__FILE__)) . '/library/Swift',
										]);