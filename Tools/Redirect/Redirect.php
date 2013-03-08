<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\Tools\Redirect;

/**
 * Class for building HTTP requests.
 *
 * @package         WebinyFramework
 * @category		Architecture
 * @subcategory		Environment
 */
 
class Redirect
{
	use \WF\Architecture\Environment,
		\WF\StdLib\StdLib;

	private $_url			= '';
	private $_params		= array();

	/**
	 * Constructor.
	 *
	 * @param string $url Url to which to redirect.
	 * @param array $params List of params that will be appended to the request.
	 */
	public function __construct($url, array $params)
	{
		$this->parseUrl($url);
		$this->_params = array_merge($this->_params, $params);

		$this->_redirect();
	}

	public function _redirect()
	{
		if($this->_url=='')
		{
			$this->exception('You must set the destination url before you can process a redirect.');
		}

		$url = $this->_buildUrl();
		header('Location:'.$url);
		die();
	}

	/**
	 * Parses the current url and extracts the parameters from the base value.
	 */
	private function parseUrl($url)
	{
		$url = $this->str($url)->lower()->trim();
		if(!$url->startsWith('https') && !$url->startsWith('http'))
		{

			$this->_url = $this->getCurrentDomain().'/'.$url->stripStartingSlash();
			if(!$url)
			{
				$this->exception('Unable to read current domain.');
			}
		}

		$urlData = $this->url($this->_url);

		$this->_url 	= $urlData->getDomain().$urlData->getPath();
		$this->_params 	= $urlData->getQuery();
	}

	/**
	 * Builds the full redirect url.
	 *
	 * @return string
	 */
	private function _buildUrl()
	{
		return http_build_url($this->_url,
			array(
				 "query" => $this->_params
			)
		);
	}
}