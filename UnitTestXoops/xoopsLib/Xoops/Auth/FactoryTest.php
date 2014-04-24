<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Auth_FactoryTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Auth\Factory';
    
    public function test___construct()
	{	
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
	public function test_getAuthConnection()
	{
		$this->markTestIncomplete();
	}
}
