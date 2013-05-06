<?php

require_once '../WebinyFramework.php';

$urlObject = new \Webiny\StdLib\StdObject\UrlObject\UrlObject('http://www.webiny.com/some-path/');
echo $urlObject->getPath();