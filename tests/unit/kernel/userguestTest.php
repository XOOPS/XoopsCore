<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class UserguestTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsGuestUser';
	protected $conn = null;

    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
    }

    public function test_isGuest()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->isGuest();
        $this->assertSame(true,$value);
    }

}
