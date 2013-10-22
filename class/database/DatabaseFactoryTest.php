<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
require_once(dirname(__FILE__).'/../../init_mini.php');

require_once(XOOPS_ROOT_PATH.'/class/xoopsload.php');
require_once(XOOPS_ROOT_PATH.'/class/preload.php');
require_once(XOOPS_ROOT_PATH.'/class/database/databasefactory.php');
require_once(XOOPS_ROOT_PATH.'/xoops_lib/Xoops/Cache.php');
require_once(XOOPS_ROOT_PATH.'/xoops_data/data/secure.php');

class DatabaseFactoryTest extends MY_UnitTestCase
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
