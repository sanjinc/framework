<?php
namespace WF\Test\WkfTest\Workflow;

class Workflow {
    use \WF\StdLib\Singleton;

    private $_activities = array();
    private $_transitions = array();

    /**
     * Singleton trait.
     * NOTE: This function must be declared static.
     * This function must return:
     * self::_getInstance();
     *
     * @return $this
     */
    static function getInstance(){
        return self::_getInstance();
    }

    public function activate(){
        echo "activate()";
    }

    public function addActivity($activity){
        $this->_activities[] = $activity;
    }

    public function addTransition($transition){
        $this->_transitions[] = $transition;
    }

}
