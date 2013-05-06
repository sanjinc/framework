<?php
namespace Webiny\Test\WkfTest;

trait Workflow
{

    static function getInstance() {
        return \Webiny\Test\WkfTest\Workflow\Workflow::getInstance();
    }

    public function addActivity($activity) {
        Workflow\Workflow::getInstance()->addActivity($activity);
    }

    public function addTransition($transition) {
        Workflow\Workflow::getInstance()->addTransition($transition);
    }

    public function activate() {
        return Workflow\Workflow::getInstance()->activate();
    }

    abstract public function createWorkflow();

}
