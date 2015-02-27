<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Auth_AdsTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'Xoops\Auth\Ads';
    
    public function test___construct()
	{
		if (!extension_loaded('ldap')) $this->markTestSkipped();
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
	public function test_authenticate()
	{
		if (!extension_loaded('ldap')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_getUPN()
	{
		if (!extension_loaded('ldap')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
}
