<?php
require_once(dirname(__DIR__) . '/init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsPreloadItemTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsPreloadItem';

    public function test___construct()
	{
		$x = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $x);
        $this->assertInstanceOf('Xoops\Core\PreloadItem', $x);
    }

}
