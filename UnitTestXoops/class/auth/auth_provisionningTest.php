<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsAuthProvisionningTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsAuthProvisionning';
	
    public function test__construct()
	{
		$auth = new Xoops_Auth(null);
		$instance = new $this->myclass($auth);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Auth_Provisioning', $instance);
    }
	
}
