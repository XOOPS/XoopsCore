<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Configuration;
use Doctrine\Common\EventManager;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsConnectionTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsConnection';
    
    public function SetUp()
	{
    }
	
    public function test_100()
	{
		$params = array();
		$config = new Configuration();
		$eventManager = new EventManager();
		$driver = new Doctrine\DBAL\Driver\PDOMySql\Driver();
		
		$instance = new $this->myclass($params, $driver, $config, $eventManager);
		$this->assertInstanceOf('XoopsConnection', $instance);
    }
	
	public function test_200()
	{
		XoopsConnection::setSafe(true);
		$x = XoopsConnection::getSafe();
		$this->assertTrue($x);

		XoopsConnection::setSafe(0);  // arg is not boolean => ignore
		$x = XoopsConnection::getSafe();
		$this->assertTrue($x);
		
		XoopsConnection::setSafe(false);
		$x = XoopsConnection::getSafe();
		$this->assertFalse($x);
	}
	
	public function test_300()
	{
		XoopsConnection::setForce(true);
		$x = XoopsConnection::getForce();
		$this->assertTrue($x);

		XoopsConnection::setForce(0);  // arg is not boolean => ignore
		$x = XoopsConnection::getForce();
		$this->assertTrue($x);
		
		XoopsConnection::setForce(false);
		$x = XoopsConnection::getForce();
		$this->assertFalse($x);
	}
	
	public function test_400()
	{
		$x = XoopsConnection::prefix('');
		$this->assertSame(XOOPS_DB_PREFIX,$x);

		$table = 'toto';
		$x = XoopsConnection::prefix($table);
		$this->assertSame(XOOPS_DB_PREFIX.'_'.$table,$x);
	}
	
	public function test_500()
	{
		//  insertPrefix($tableName, array $data, array $types = array())
	}
	
	public function test_600()
	{
		//  updatePrefix($tableName, array $data, array $identifier, array $types = array())
	}
	
	public function test_700()
	{
		//  deletePrefix($tableName, array $identifier)
	}
	
	public function test_800()
	{
		/*
		executeQuery(
        $query,
        array $params = array(),
        $types = array(),
        \Doctrine\DBAL\Cache\QueryCacheProfile $qcp = null
		*/
	}
	
	public function test_900()
	{
		//  executeUpdate($query, array $params = array(), array $types = array())
	}
	
	public function test_1000()
	{
		//  beginTransaction()
	}
	
	public function test_1100()
	{
		//  commit()
	}
	
	public function test_1200()
	{
		//  rollBack()
	}
	
	public function test_1300()
	{
		//  query()
	}
	
	public function test_1400()
	{
		//  queryFromFile($file)
	}
	
	public function test_1500()
	{
		//  quoteSlash($input)
	}
	
	public function test_1600()
	{
		//  createXoopsQueryBuilder()
	}
	
	
	
}
