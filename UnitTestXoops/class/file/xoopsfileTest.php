<?php
require_once(__DIR__.'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFileTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsFile';

    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
}
