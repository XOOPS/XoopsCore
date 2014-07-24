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
		$this->markTestIncomplete();
    }
	
	public function test_updateAll()
	{
		$this->markTestIncomplete();
    }
}
