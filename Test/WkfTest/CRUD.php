<?php
namespace WF\Test\WkfTest;

abstract class CRUD
{
    abstract function load($id);

    abstract function populate();

    abstract function save();
}
