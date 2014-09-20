<?php
require_once(__DIR__.'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsCacheFileTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCacheFile';
	
    public function test__construct()
	{
		$instance = new $this->myclass(null);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Cache_File', $instance);
    }
	
}
