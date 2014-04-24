<?php
require_once(dirname(__FILE__).'/../../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ReadTest extends MY_UnitTestCase
{

	protected $myClass = 'Xoops\Core\Kernel\Model\Read';
	protected $myAbstractClass = 'Xoops\Core\Kernel\XoopsModelAbstract';
	
    public function SetUp()
	{
    }

    public function test___construct()
	{
        $instance=new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf($this->myAbstractClass, $instance);
	}
	
	public function test_getAll()
	{
		$this->markTestIncomplete();
    }
	
	public function test_getObjects()
	{
		$this->markTestIncomplete();
    }
	
	public function test_getList()
	{
		$this->markTestIncomplete();
    }
	
	public function test_getIds()
	{
		$this->markTestIncomplete();
    }
}
