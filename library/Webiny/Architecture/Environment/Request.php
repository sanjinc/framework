<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link         http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright    Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license      http://www.webiny.com/framework/license
 * @package      WebinyFramework
 */
namespace Webiny\Architecture\Environment;

/**
 * Class for working and gathering data about HTTP requests.
 *
 * @category       WebinyFramework
 * @package        Architecture
 * @subpackage     Environment
 */
class Request
{
    use \Webiny\StdLib\StdLib,
        \Webiny\StdLib\Singleton;

    /**
     * Returns visitors IP address.
     * @TODO: Upgrade to support IPv6.
     *
     * @return string
     */
    public function getVisitorsIp() {
        $server = $this->arr($_SERVER);

        $ip = $server->key('HTTP_CLIENT_IP');
        if($ip) {
            return $ip;
        }

        $ip = $server->key('HTTP_X_FORWARDED_FOR');
        if($ip) {
            return $ip;
        }

        return $server->key('REMOTE_ADDR');
    }

    /**
     * Checks if current connection is secured.
     *
     * @return bool
     */
    public function isConnectionSecured() {
        $url = $this->getCurrentUrl(true);

        if($url->getSchema() == 'https') {
            return true;
        }

        return false;
    }

    /**
     * Returns current domain with its scheme.
     * Example return: http://www.webiny.com
     * The value doesn't contain a trailing slash.
	 * If you want to get only the host name (www.webiny.com) use the getHost method.
     *
     * @return bool|string
     */
    public function getCurrentDomain() {
        return $this->getCurrentUrl(true)->getDomain();
    }

	/**
	 * Returns the name of the host. Example: www.webiny.com
	 * If you want to get the full domain name with schema use getCurrentDomain method.
	 *
	 * @return bool|string
	 */
	function getHost(){
		return $this->getCurrentUrl(true)->getHost();
	}

    /**
     * Returns current url as string or as UrlStandardObject.
     *
     * @param bool $asUrlStandardObject
     *
     * @return string|\Webiny\StdLib\StdObject\UrlObject\UrlObject
     */
    public function getCurrentUrl($asUrlStandardObject) {
        $pageURL = 'http';
        $https = $this->val($_SERVER["HTTPS"], 'off');
        if($https == "on") {
            $pageURL .= "s";
        }

        $pageURL .= "://";

        $port = $this->val($_SERVER["SERVER_PORT"], '80');
        if($port != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }

        if($asUrlStandardObject) {
            return $this->url($pageURL);
        } else {
            return $pageURL;
        }
    }

    /**
     * Returns $_POST or $_GET parameter from the current request.
     *
     * @param string $paramName            Name of the parameter.
     * @param string $paramType            Can be 'get' or 'post'.
     * @param bool   $paramDefaultValue    Default value that will be returned if the parameter is not found.
     *
     * @return bool|mixed
     */
    public function getRequestParameter($paramName, $paramType = 'get', &$paramDefaultValue = false) {
        // initial checks
        $paramType = $this->_validateParamType($paramType);
        $paramName = $this->str($paramName);

        // check if name is a multi-level array (recursion)
        if($paramName->contains('[')) {
            $matches = $paramName->matches('|\[(.*?)\]|');
            if($matches && $this->val($matches[1]) && $this->isArray($matches[1])) {
                // get original name
                $rmatches = $paramName->matches("|(.*?)\[|", false);
                $root = $rmatches[1];
                $rootArray = $this->getRequestParameter($root, $paramType->val(), false);
                if(!$rootArray) {
                    return $paramDefaultValue;
                }

                foreach ($matches[1] as $key) {
                    $cArray = ($this->isArray($rootArray) && $this->val($rootArray[$key])) ? $rootArray[$key] : false;
                    if($cArray === false) {
                        return $paramDefaultValue;
                    }
                    $rootArray = $cArray;
                }

                return $rootArray;
            }
        }

        // get the params
        if($paramType->equals('get')) {
            return $this->val($_GET[$paramType], $paramDefaultValue);
        } else {
            return $this->val($_POST[$$paramType], $paramDefaultValue);
        }
    }

    /**
     * Singleton trait.
     * This function must return:
     * self::_getInstance();
     *
     * @return Request
     */
    static function getInstance() {
        return self::_getInstance();
    }

    /**
     * Validates parameter type (post or get).
     *
     * @param $paramType    Can be 'post' or 'get'.
     *
     * @return \Webiny\StdLib\StdObject\StringObject\StringObject
     */
    private function _validateParamType($paramType) {
        $paramType = $this->str($paramType)->lower()->trim();
        if(!$paramType->equals('get') && !$paramType->equals('post')) {
            $this->exception('Invalid parameter type. It can be either "post" or "get".');
        }

        return $paramType;
    }
}