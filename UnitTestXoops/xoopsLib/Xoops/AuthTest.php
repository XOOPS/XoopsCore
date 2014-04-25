<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

class Xoops_Auth_AbstractTestInstance extends Xoops\Auth\AuthAbstract
{
	public function authenticate($uname, $pwd = null) { return false; }
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_AuthTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Auth_AbstractTestInstance';

    public function SetUp()
	{
    }

    public function test___construct()
	{
		$dao = 'dao';
		$instance = new $this->myclass($dao);
		$this->assertInstanceOf($this->myclass, $instance);
    }

	public function test_authenticate()
	{
		$dao = 'dao';
		$instance = new $this->myclass($dao);
		$uname = 'uname';
		$pwd = 'pwd';
		$x = $instance->authenticate($uname, $pwd);
		$this->assertFalse($x);
	}

	public function test_setErrors()
	{
		$dao = 'dao';
		$instance = new $this->myclass($dao);
		$errno = 1;
		$error = 'error';
		$instance->setErrors($errno, $error);
		$x = $instance->getErrors();
		$this->assertTrue(is_array($x));
		$this->assertTrue($x[$errno]==$error);
	}

	public function test_getErrors()
	{
		$this->assertTrue(true); // allready tested in previous test
	}

	public function test_getHtmlErrors()
	{
		$dao = 'dao';
		$instance = new $this->myclass($dao);
		$errno = 1;
		$error = 'error';
		$instance->setErrors($errno, $error);
		$x = $instance->getHtmlErrors();
		$this->assertTrue(is_string($x));
	}

}
