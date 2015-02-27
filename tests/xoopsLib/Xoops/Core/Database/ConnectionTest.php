<?php
require_once(dirname(__FILE__).'/../../../../init_mini.php');


use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Configuration;
use Doctrine\Common\EventManager;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = '\Xoops\Core\Database\Connection';

    public function SetUp()
	{
    }

    public function test___construct()
	{
		$params = array();
		$config = new Configuration();
		$eventManager = new EventManager();
		$driver = new Doctrine\DBAL\Driver\PDOMySql\Driver();

		$instance = new $this->myclass($params, $driver, $config, $eventManager);
		$this->assertInstanceOf('\Xoops\Core\Database\Connection', $instance);
    }

	public function test_setSafe()
	{
		$class = $this->myclass;
		$class::setSafe(true);
		$x = $class::getSafe();
		$this->assertTrue($x);

		$class::setSafe(0);  // arg is not boolean => ignore
		$x = $class::getSafe();
		$this->assertTrue($x);

		$class::setSafe(false);
		$x = $class::getSafe();
		$this->assertFalse($x);
	}

	public function test_getSafe()
	{
	}

	public function test_setForce()
	{
		$class = $this->myclass;
		$class::setForce(true);
		$x = $class::getForce();
		$this->assertTrue($x);

		$class::setForce(0);  // arg is not boolean => ignore
		$x = $class::getForce();
		$this->assertTrue($x);

		$class::setForce(false);
		$x = $class::getForce();
		$this->assertFalse($x);
	}

	public function test_getForce()
	{
	}

	public function test_prefix()
	{
		$class = $this->myclass;
		$x = $class::prefix('');
		$this->assertSame(XOOPS_DB_PREFIX,$x);

		$table = 'toto';
		$x = $class::prefix($table);
		$this->assertSame(XOOPS_DB_PREFIX.'_'.$table,$x);
	}

	public function test_insertPrefix()
	{
		//  insertPrefix($tableName, array $data, array $types = array())
	}

	public function test_updatePrefix()
	{
		//  updatePrefix($tableName, array $data, array $identifier, array $types = array())
	}

	public function test_deletePrefix()
	{
		//  deletePrefix($tableName, array $identifier)
	}

	public function test_executeQuery()
	{
		/*
		executeQuery(
        $query,
        array $params = array(),
        $types = array(),
        \Doctrine\DBAL\Cache\QueryCacheProfile $qcp = null
		*/
	}

	public function test_executeUpdate()
	{
		//  executeUpdate($query, array $params = array(), array $types = array())
	}

	public function test_beginTransaction()
	{
		//  beginTransaction()
	}

	public function test_commit()
	{
		//  commit()
	}

	public function test_rollBack()
	{
		//  rollBack()
	}

	public function test_query()
	{
		//  query()
	}

	public function test_queryFromFile()
	{
		//  queryFromFile($file)
	}

	public function test_quoteSlash()
	{
		//  quoteSlash($input)
	}

	public function test_createXoopsQueryBuilder()
	{
		//  createXoopsQueryBuilder()
	}

}
