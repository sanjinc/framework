<?php
namespace WF\Test;

use WF\StdLib\StdObject\FileObject\FileObject;

require_once '../WebinyFramework.php';
$f = new FileObject(WF_ABS_PATH.'/Test/pero/test.txt');
$f->write('asd');
echo $f->getExtension();
echo $f->getMTime();
$f->delete();