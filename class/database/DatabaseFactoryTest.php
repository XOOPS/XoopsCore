<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsDatabaseFactoryTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsDatabaseFactory';
    
    public function SetUp()
	{
    }
	
    public function test_100()
	{
		$instance = XoopsDatabaseFactory::getDatabaseConnection();
		$this->assertInstanceOf('XoopsMySQLDatabaseProxy', $instance);
		$instance2 = XoopsDatabaseFactory::getDatabaseConnection();
		$this->assertSame($instance, $instance2);
		$this->assertInstanceOf('XoopsConnection', $instance->conn);
		$driver = $instance->conn->getDriver();
		$driver_conn = $driver->connect();
		$this->assertInstanceOf('\Doctrine\DBAL\Driver\PDOConnection', $driver_conn);
        $this->assertSame(XOOPS_DB_PREFIX.'_test', $instance->prefix('test'));
        $this->assertSame(XOOPS_DB_PREFIX, $instance->prefix());
    }

	/*
	public function test_200()
	{
        // removed because this function will be removed all together.
		$instance = XoopsDatabaseFactory::getDatabase();
		if (!defined('XOOPS_DB_PROXY'))
			$this->assertInstanceOf('XoopsMySQLDatabaseSafe', $instance);
		else
			$this->assertInstanceOf('XoopsMySQLDatabaseProxy', $instance);
			
		$instance2 = XoopsDatabaseFactory::getDatabase();
		$this->assertSame($instance, $instance2);
    }
	*/
	
}
