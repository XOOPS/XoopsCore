<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Auth_LdapTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Auth_Ldap';
    
    public function test___construct()
	{	
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Auth', $instance);

    }
	
	public function test_cp1252_to_utf8()
	{
		if (!extension_loaded('ldap')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_authenticate()
	{
		if (!extension_loaded('ldap')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_getUserDN()
	{
		if (!extension_loaded('ldap')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_getFilter()
	{
		$this->markTestIncomplete();
	}
	
	public function test_loadXoopsUser()
	{
		if (!extension_loaded('ldap')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
}
