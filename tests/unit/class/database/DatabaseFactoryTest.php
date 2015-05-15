<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsDatabaseFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsDatabaseFactory';

    public function setUp()
	{
    }

    public function test_getDatabaseConnection()
	{
		$class = $this->myClass;
		$instance = $class::getDatabaseConnection();
		$this->assertStringStartsWith('XoopsMySQLDatabase', get_class($instance));
		
		$instance2 = $class::getDatabaseConnection();
		$this->assertSame($instance, $instance2);
		$this->assertInstanceOf('\Xoops\Core\Database\Connection', $instance->conn);
		$driver = $instance->conn->getDriver();
		$driver_conn = $driver->connect(array());
		$this->assertInstanceOf('\Doctrine\DBAL\Driver\PDOConnection', $driver_conn);
        $this->assertSame(\XoopsBaseConfig::get('db-prefix').'_test', $instance->prefix('test'));
        $this->assertSame(\XoopsBaseConfig::get('db-prefix'), $instance->prefix());
    }

}
