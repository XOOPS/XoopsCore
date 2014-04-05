<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsAuthLdapTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsAuthLdap';
	
    public function test__construct()
	{
		if (!extension_loaded('LDAP')) return;
		
		$instance = new $this->myclass(null);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Auth_Ldap', $instance);
    }
	
}
