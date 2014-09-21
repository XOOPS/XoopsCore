<?php
require_once(dirname(dirname(__DIR__)) . '/init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsCacheTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCache';

    public function test__construct()
	{
		$instance = new $this->myclass(null);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Cache', $instance);
    }

}
