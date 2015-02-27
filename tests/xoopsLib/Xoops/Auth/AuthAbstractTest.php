<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

class AuthAbstractTestInstance extends Xoops\Auth\AuthAbstract
{
    function authenticate($uname, $pwd = null) {return false;}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class AuthAbstractTest extends \PHPUnit_Framework_TestCase
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
		// allready tested in test_setErrors
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
