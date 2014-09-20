<?php
require_once(__DIR__.'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsCacheApcTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCacheApc';
	
    public function test__construct()
	{
		$instance = new $this->myclass(null);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Cache_Apc', $instance);
    }
	
}
