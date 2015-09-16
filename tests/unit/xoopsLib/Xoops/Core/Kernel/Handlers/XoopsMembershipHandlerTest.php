<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsMembershipHandler;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MembershipHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='Xoops\Core\Kernel\Handlers\XoopsMembershipHandler';
	protected $conn = null;

    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
		$this->assertRegExp('/^.*groups_users_link$/',$instance->table);
		$this->assertSame('\\Xoops\\Core\\Kernel\\Handlers\\XoopsMembership',$instance->className);
		$this->assertSame('linkid',$instance->keyName);
		$this->assertSame('groupid',$instance->identifierName);
    }

    public function testContracts()
    {
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf('\\Xoops\\Core\\Kernel\\Handlers\\XoopsMembershipHandler', $instance);
        $this->assertInstanceOf('\\Xoops\\Core\\Kernel\\XoopsPersistableObjectHandler', $instance);
    }

    public function test_getGroupsByUser()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getGroupsByUser(1);
        $this->assertTrue(is_array($value));
    }

    public function test_getGroupsByGroup()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getGroupsByGroup(1);
        $this->assertSame(null,$value);
    }

}
