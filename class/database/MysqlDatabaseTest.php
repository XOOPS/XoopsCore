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
class XoopsMySQLDatabaseTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsMySQLDatabase';
    
    public function SetUp()
	{
    }
	
    public function test_100()
	{
		$instance = new $this->myclass();
    }
	
	public function test_200()
	{
		$instance = new $this->myclass();
		// connect
	}
	
	public function test_300()
	{
		$instance = new $this->myclass();
		$sequence = 0;
		$x = $instance->genId($sequence);
		$this->assertSame(0,$x);
	}
	
	public function test_400()
	{
		$instance = new $this->myclass();
		// fetchRow
	}
	
	public function test_500()
	{
		$instance = new $this->myclass();
		// fetchArray
	}
	
	public function test_600()
	{
		$instance = new $this->myclass();
		// fetchBoth
	}
	
	public function test_700()
	{
		$instance = new $this->myclass();
		// fetchObject
	}
	
	public function test_800()
	{
		$instance = new $this->myclass();
		// getInsertId
	}
	
	public function test_900()
	{
		$instance = new $this->myclass();
		// getRowsNum
	}
	
	public function test_1000()
	{
		$instance = new $this->myclass();
		// getAffectedRows
	}
	
	public function test_1100()
	{
		$instance = new $this->myclass();
		// close
	}
	
	public function test_1200()
	{
		$instance = new $this->myclass();
		// freeRecordSet
	}
	
	public function test_1300()
	{
		$instance = new $this->myclass();
		// error
	}
	
	public function test_1400()
	{
		$instance = new $this->myclass();
		// errno
	}
	
	public function test_1500()
	{
		$instance = new $this->myclass();
		// quoteString
	}
	
	public function test_1600()
	{
		$instance = new $this->myclass();
		// quote
	}
	
	public function test_1700()
	{
		$instance = new $this->myclass();
		// queryF
	}
	
	public function test_1800()
	{
		$instance = new $this->myclass();
		// query
	}
	
	public function test_1900()
	{
		$instance = new $this->myclass();
		// queryFromFile
	}
	
	public function test_2000()
	{
		$instance = new $this->myclass();
		// getFieldName
	}
	
	public function test_2100()
	{
		$instance = new $this->myclass();
		// getFieldType
	}
	
	public function test_2200()
	{
		$instance = new $this->myclass();
		// getFieldsNum
	}
	
}
