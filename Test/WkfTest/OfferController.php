<?php
namespace WF\Test\WkfTest;

class OfferController
{
    function  action()
    {
        $wkf = OfferWorkflow::getInstance();
        die(print_r($wkf));
        $wkf->activate();
    }

}

