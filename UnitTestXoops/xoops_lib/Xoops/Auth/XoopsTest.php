<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Auth_XoopsTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Auth_Xoops';
    
    public function test___construct()
	{
		$conn = XoopsDatabaseFactory::getConnection();
		
		$instance = new $this->myclass($conn);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Auth', $instance);
    }
	
	public function test_authenticate()
	{
		$this->markTestIncomplete();
	}
}
