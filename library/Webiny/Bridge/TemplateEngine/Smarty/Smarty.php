<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\TemplateEngine\Smarty;

require_once dirname(__FILE__).'/../../../../Smarty/libs/Smarty.class.php';

use Webiny\Bridge\TemplateEngine\TemplateEngineInterface;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\ServiceManager\ServiceManagerTrait;
use Webiny\Component\TemplateEngine\Drivers\Smarty\SmartyExtensionInterface;
use Webiny\Component\TemplateEngine\Plugin;

/**
 * Template engine bridge for Smarty library.
 *
 * @package         Webiny\Bridge\TemplateEngine
 */

class Smarty implements TemplateEngineInterface
{
	use ServiceManagerTrait;

	/**
	 * @var \Smarty
	 */
	private $_smarty;


	/**
	 * Base constructor.
	 *
	 * @param ConfigObject $config Configuration for the template engine.
	 *
	 * @throws SmartyException
	 */
	function __construct(ConfigObject $config) {
		$this->_smarty = new \Smarty();

		// compile_dir
		$compileDir = $config->get('compile_dir', false);
		if(!$compileDir){
			throw new SmartyException('Configuration error, "compile_dir" is missing.');
		}
		$this->setCompileDir($compileDir);

		// cache_dir
		$cacheDir = $config->get('cache_dir', false);
		if(!$cacheDir){
			throw new SmartyException('Configuration error, "cache_dir" is missing.');
		}
		$this->setCacheDir($cacheDir);

		// force_compile
		if($config->get('force_compile', false)){
			$this->setForceCompile(true);
		}
		
		// register extensions
		$extensions = $this->servicesByTag('smarty.extension',
										   '\Webiny\Component\TemplateEngine\Drivers\Smarty\SmartyExtensionInterface');
		/**
		 * @var $e SmartyExtensionInterface
		 */
		if(count($extensions)>0){
			$methods = get_class_methods('\Webiny\Component\TemplateEngine\Drivers\Smarty\SmartyExtensionInterface');
			foreach($extensions as $e){
				foreach($methods as $m){
					if($m!='getName'){
						$plugins = $e->{$m}();
						foreach($plugins as $p){
							$this->registerPlugin($p);
						}
					}
				}
			}
		}
		
	}

	/**
	 * Set Smarty compile dir.
	 *
	 * @param string $compileDir Absolute path where to store compiled files.
	 */
	function setCompileDir($compileDir){
		$this->_smarty->setCompileDir($compileDir);
	}

	/**
	 * Set Smarty cache dir.
	 *
	 * @param string $cacheDir Absolute path where to store cache files.
	 */
	function setCacheDir($cacheDir){
		$this->_smarty->setCacheDir($cacheDir);
	}

	/**
	 * Force to re-compile the templates on every refresh.
	 *
	 * @param bool $forceCompile
	 */
	function setForceCompile($forceCompile){
		$this->_smarty->force_compile = $forceCompile;
	}

	/**
	 * Fetch the template from the given location, parse it and return the output.
	 *
	 * @param string $template Path to the template.
	 * @param array  $parameters A list of parameters to pass to the template.
	 * @return string Parsed template.
	 */
	function fetch($template, $parameters = []) {
		$this->_smarty->assign($parameters);
		return $this->_smarty->fetch($template);
	}

	/**
	 * Fetch the template from the given location, parse it and output the result to the browser.
	 *
	 * @param string $template Path to the template.
	 * @param array  $parameters A list of parameters to pass to the template.
	 *
	 * @return void
	 */
	function render($template, $parameters = []) {
		echo $this->_smarty->fetch($template, $parameters);
	}

	/**
	 * Assign a variable and its value into the template engine.
	 *
	 * @param string $var   Variable name.
	 * @param mixed  $value Variable value.
	 *
	 * @return void
	 */
	function assign($var, $value) {
		$this->_smarty->assign($var, $value);
	}

	/**
	 * Register a plugin for the template engine.
	 *
	 * @param Plugin $plugin
	 *
	 * @throws \Exception|SmartyException
	 * @return void
	 */
	function registerPlugin(Plugin $plugin) {
		try{
			$this->_smarty->registerPlugin($plugin->getType(),
										   $plugin->getName(),
										   $plugin->getCallbackFunction(),
										   $plugin->getAttribute('cachable', true),
										   $plugin->getAttribute('cache_attr', null)
			);
		}catch (\SmartyException $e){
			throw new SmartyException($e);
		}

	}

	/**
	 * Root path where the templates are stored.
	 *
	 * @param string $path Absolute path to the directory that holds the templates.
	 *
	 * @return void
	 */
	function setTemplatePath($path) {
		$this->_smarty->setTemplateDir($path);
	}
}