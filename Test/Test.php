<?php
namespace WF\Test;

use WF\StdLib\StdObject\ArrayObject\ArrayObject;
use WF\StdLib\StdObject\FileObject\FileObject;
use WF\StdLib\StdObject\StringObject\StringObject;

require_once '../WebinyFramework.php';

$string = 'Pero ide u ducan i nikako da zapamti sve sastojke.';

$s = new StringObject($string);
echo $s->truncate(20, '...');