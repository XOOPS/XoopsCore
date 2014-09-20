<?php
require_once(__DIR__.'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsDatabaseFactoryTest extends MY_UnitTestCase
{
    protected $myClass = 'XoopsDatabaseFactory';

    public function SetUp()
	{
    }

    public function test_getDatabaseConnection()
	{
		$class = $this->myClass;
		$instance = $class::getDatabaseConnection();
        if (!defined('XOOPS_DB_PROXY'))
			$this->assertInstanceOf('XoopsMySQLDatabaseSafe', $instance);
		else
			$this->assertInstanceOf('XoopsMySQLDatabaseProxy', $instance);
		$instance2 = $class::getDatabaseConnection();
		$this->assertSame($instance, $instance2);
		$this->assertInstanceOf('\Xoops\Core\Database\Connection', $instance->conn);
		$driver = $instance->conn->getDriver();
		$driver_conn = $driver->connect(array());
		$this->assertInstanceOf('\Doctrine\DBAL\Driver\PDOConnection', $driver_conn);
        $this->assertSame(XOOPS_DB_PREFIX.'_test', $instance->prefix('test'));
        $this->assertSame(XOOPS_DB_PREFIX, $instance->prefix());
    }

}
