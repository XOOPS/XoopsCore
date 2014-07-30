<?php
require_once(dirname(__FILE__).'/../../../../../init.php');

use Xoops\Core\Kernel\Model\Write;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class WriteTest extends MY_UnitTestCase
{
	protected $conn = null;
	
	protected $myClass = 'Xoops\Core\Kernel\Model\Write';
	protected $myAbstractClass = 'Xoops\Core\Kernel\XoopsModelAbstract';

    public function SetUp()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		$this->conn = $db->conn;
    }
	
    public function test___construct()
	{
        $instance=new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf($this->myAbstractClass, $instance);
	}
	
	public function test_cleanVars()
	{
		$this->markTestIncomplete();
    }
	
	public function test_insert()
	{
		$this->markTestIncomplete();
    }
	
	public function test_delete()
	{
		$this->markTestIncomplete();
    }
	
	public function test_deleteAll()
	{
        $instance=new $this->myClass();
        
		$handler = new XoopsGroupHandler($this->conn);
		$result = $instance->setHandler($handler);
        
        $criteria = new Criteria('groupid');
        
        $x = $instance->deleteAll($criteria);
        $this->assertSame(0, $x);
    }
	
	public function test_updateAll()
	{
        $instance=new $this->myClass();
        
		$handler = new XoopsGroupHandler($this->conn);
		$result = $instance->setHandler($handler);
        
        $criteria = new Criteria('groupid');
        
        $x = $instance->updateAll($criteria);
        $this->assertSame(0, $x);
    }
}
