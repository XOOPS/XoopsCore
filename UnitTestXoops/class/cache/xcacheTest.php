<?php
require_once(dirname(dirname(__DIR__)) . '/init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsCacheXcacheTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCacheXcache';

    public function test__construct()
	{
		$instance = new $this->myclass(null);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Cache_Xcache', $instance);
    }

}
