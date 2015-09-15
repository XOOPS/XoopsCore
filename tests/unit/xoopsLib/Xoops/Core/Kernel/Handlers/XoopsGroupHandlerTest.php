<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsGroupHandler;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class GroupHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='Xoops\Core\Kernel\Handlers\XoopsGroupHandler';
	protected $conn = null;

    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
		$this->assertRegExp('/^.*groups$/',$instance->table);
		$this->assertSame('\\Xoops\\Core\\Kernel\\Handlers\\XoopsGroup',$instance->className);
		$this->assertSame('groupid',$instance->keyName);
		$this->assertSame('name',$instance->identifierName);
    }

    public function testContracts()
    {
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf('\\Xoops\\Core\\Kernel\\Handlers\\XoopsGroupHandler', $instance);
        $this->assertInstanceOf('\\Xoops\\Core\\Kernel\\XoopsPersistableObjectHandler', $instance);
    }

}
