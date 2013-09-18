<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\TemplateEngine\Drivers\Smarty;

/**
 * This is the class that you should extend and overwrite the abstract methods if you wish to create one,
 * or a whole set of plugins for Smarty template engine.
 *
 * @package         Webiny\Bridge\TemplateEngine\Drivers\Smarty
 */

abstract class SmartyExtension implements SmartyExtensionInterface
{

	/**
	 * Register template functions.
	 *
	 * @link http://www.smarty.net/docs/en/plugins.functions.tpl
	 *
	 * @return array of SmartySimplePlugin
	 */
	function getFunctions() {
		return [];
	}

	/**
	 * Register modifiers.
	 * Modifiers are little functions that are applied to a variable in the template before it is displayed
	 * or used in some other context. Modifiers can be chained together.
	 *
	 * @link http://www.smarty.net/docs/en/plugins.modifiers.tpl
	 *
	 * @return array of SmartySimplePlugin
	 */
	function getModifiers() {
		return [];
	}

	/**
	 * Register block functions.
	 * Block functions are functions of the form: {func} .. {/func}.
	 * In other words, they enclose a template block and operate on the contents of this block.
	 *
	 * @link http://www.smarty.net/docs/en/plugins.block.functions.tpl
	 *
	 * @return array of SmartySimplePlugin
	 */
	function getBlockFunctions() {
		return [];
	}

	/**
	 * Register compiler functions.
	 * Compiler functions are called only during compilation of the template.
	 * They are useful for injecting PHP code or time-sensitive static content into the template.
	 *
	 * @link http://www.smarty.net/docs/en/plugins.compiler.functions.tpl
	 *
	 * @return array of SmartySimplePlugin
	 */
	function getCompilerFunctions() {
		return [];
	}

	/**
	 * Register pre filters.
	 * Prefilters are used to process the source of the template immediately before compilation.
	 *
	 * @link http://www.smarty.net/docs/en/plugins.prefilters.postfilters.tpl
	 *
	 * @return array of SmartySimplePlugin
	 */
	function getPreFilters() {
		return [];
	}

	/**
	 * Register post filters.
	 * Postfilters are used to process the compiled output of the template (the PHP code) immediately
	 * after the compilation is done but before the compiled template is saved to the filesystem.
	 *
	 * @link http://www.smarty.net/docs/en/plugins.prefilters.postfilters.tpl
	 *
	 * @return array of SmartySimplePlugin
	 */
	function getPostFilters() {
		return [];
	}

	/**
	 * Register output filters.
	 * Output filter plugins operate on a template's output, after the template is loaded and executed,
	 * but before the output is displayed.
	 *
	 * @link http://www.smarty.net/docs/en/plugins.outputfilters.tpl
	 *
	 * @return array of SmartySimplePlugin
	 */
	function getOutputFilters() {
		return [];
	}

	/**
	 * Register resources.
	 * Resource plugins are meant as a generic way of providing template sources or PHP script components to Smarty.
	 * Some examples of resources: databases, LDAP, shared memory, sockets, and so on.
	 *
	 * @link http://www.smarty.net/docs/en/plugins.resources.tpl
	 *
	 * @return array of SmartySimplePlugin
	 */
	function getResources() {
		return [];
	}

	/**
	 * Register insets.
	 * Insert plugins are used to implement functions that are invoked by {insert} tags in the template.
	 *
	 * @link http://www.smarty.net/docs/en/plugins.inserts.tpl
	 *
	 * @return array of SmartySimplePlugin
	 */
	function getInserts() {
		return [];
	}
}