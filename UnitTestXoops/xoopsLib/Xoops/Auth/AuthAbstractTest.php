<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

class AuthAbstractTestInstance extends Xoops\Auth\AuthAbstract
{

    function authenticate($uname, $pwd = null) {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class AuthAbstractTest extends MY_UnitTestCase
{
    protected $myclass = 'AuthAbstractTestInstance';

    public function test___construct()
	{
		$conn = \Xoops\Core\Database\Factory::getConnection();

		$instance = new $this->myclass($conn);
		$this->assertInstanceOf($this->myclass, $instance);
    }

	public function test_setErrors()
	{
		$this->markTestIncomplete();
	}
}
