<?php
require_once(dirname(__DIR__) . '/init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsPreloadTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsPreload';

    public function test___construct()
	{
		$class = $this->myclass;
		$x = $class::getInstance();
        $this->assertInstanceOf('\Xoops\Core\Events', $x);
    }

}
