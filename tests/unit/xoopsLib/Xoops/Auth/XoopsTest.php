<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Auth_XoopsTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops\Auth\Xoops';

    public function test___construct()
	{
		$conn = \Xoops\Core\Database\Factory::getConnection();

		$instance = new $this->myClass($conn);
		$this->assertInstanceOf($this->myClass, $instance);
		$this->assertInstanceOf('Xoops\Auth\AuthAbstract', $instance);
    }

	public function test_authenticate()
	{
		$conn = \Xoops\Core\Database\Factory::getConnection();

		$instance = new $this->myClass($conn);
		
		$uname = 'admin';
		$pwd = 'pwd';
		$value = $instance->authenticate($uname, $pwd);
		$this->assertSame(false, $value);
	}
}
