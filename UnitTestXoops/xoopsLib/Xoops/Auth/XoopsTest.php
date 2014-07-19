<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Auth_XoopsTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Auth\Xoops';

    public function test___construct()
	{
		$conn = \Xoops\Core\Database\Factory::getConnection();

		$instance = new $this->myclass($conn);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops\Auth\AuthAbstract', $instance);
    }

	public function test_authenticate()
	{
		$conn = \Xoops\Core\Database\Factory::getConnection();

		$instance = new $this->myclass($conn);
		
		$uname = 'admin';
		$pwd = 'pwd';
		$value = $instance->authenticate($uname, $pwd);
		$this->assertInstanceOf('XoopsUser', $value);
	}
}
