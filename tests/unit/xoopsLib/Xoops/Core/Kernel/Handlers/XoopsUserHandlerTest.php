<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsUserHandler;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class UserHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsUserHandler';
	protected $conn = null;

    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*users$/',$instance->table);
		$this->assertSame('\Xoops\Core\Kernel\Handlers\XoopsUser',$instance->className);
		$this->assertSame('uid',$instance->keyName);
		$this->assertSame('uname',$instance->identifierName);
    }

}