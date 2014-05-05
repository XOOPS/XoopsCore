<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

class Xoops_Auth_ProvisioningTest_AuthAbstractInstance extends Xoops\Auth\AuthAbstract
{

    function authenticate($uname, $pwd = null) {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Auth_ProvisioningTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Auth\Provisioning';

    public function test___construct()
	{
		$conn = \Xoops\Core\Database\Factory::getConnection();
		$auth = new Xoops_Auth_ProvisioningTest_AuthAbstractInstance($conn);

		$instance = new $this->myclass($auth);
		$this->assertInstanceOf($this->myclass, $instance);
    }

	public function test_getInstance()
	{
		$this->markTestIncomplete();
	}

	public function test_getXoopsUser()
	{
		$this->markTestIncomplete();
	}

	public function test_sync()
	{
		$this->markTestIncomplete();
	}

	public function test_add()
	{
		$this->markTestIncomplete();
	}

	public function test_change()
	{
		$this->markTestIncomplete();
	}

	public function test_delete()
	{
		$this->markTestIncomplete();
	}

	public function test_suspend()
	{
		$this->markTestIncomplete();
	}

	public function test_restore()
	{
		$this->markTestIncomplete();
	}

	public function test_resetpwd()
	{
		$this->markTestIncomplete();
	}
}
