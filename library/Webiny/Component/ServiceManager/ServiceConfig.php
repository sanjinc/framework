<?php
namespace Webiny\Component\ServiceManager;


use Webiny\StdLib\StdLibTrait;

class ServiceConfig
{
	use StdLibTrait;

	private $_class;
	private $_arguments;
	private $_calls;
	private $_scope;

	function __construct($class, $arguments = null, $calls = null, $scope = ServiceScope::CONTAINER) {
		$this->_class = $class;
		$this->_arguments = $arguments;
		$this->_calls = $calls;
		if($this->isNull($scope)){
			$scope = ServiceScope::CONTAINER;
		}
		$this->_scope = $scope;
	}

	/**
	 * @param null $arguments
	 */
	public function setArguments($arguments) {
		$this->_arguments = $arguments;
	}

	/**
	 * @return mixed
	 */
	public function getScope() {
		return $this->_scope;
	}

	/**
	 * @return mixed
	 */
	public function getArguments() {
		return $this->_arguments;
	}

	/**
	 * @return mixed
	 */
	public function getCalls() {
		return $this->_calls;
	}

	/**
	 * @return mixed
	 */
	public function getClass() {
		return $this->_class;
	}



}