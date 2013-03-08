<?php
require_once '../WebinyFramework.php';

trait Workflow
{
	use \WF\StdLib\Singleton;


	static function getInstance()
	{
		echo 'neki instance';
		return self::_getInstance();
	}
}

class OfferWorkflow implements OfferWorkflowInst
{
	use Workflow;


	public function action()
	{
		echo 'u akciji<br/>';
	}
}

$a = OfferWorkflow::getInstance()->action();