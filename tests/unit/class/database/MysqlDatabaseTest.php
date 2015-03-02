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
class XoopsMySQLDatabaseTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsMySQLDatabase';
	
    public function test___construct()
	{
		$instance = new $this->myclass();
    }
	
    public function test___publicProperties()
	{
		$items = array('conn');
		foreach($items as $item) {
			$prop = new ReflectionProperty($this->myclass,$item);
			$this->assertTrue($prop->isPublic());
		}
    }
	
	public function test_connect()
	{
		$instance = new $this->myclass();
		// connect
	}
	
	public function test_genId()
	{
		$instance = new $this->myclass();
		$sequence = 0;
		$x = $instance->genId($sequence);
		$this->assertSame(0,$x);
	}
	
	public function test_fetchRow()
	{
		$instance = new $this->myclass();
		// fetchRow
	}
	
	public function test_fetchArray()
	{
		$instance = new $this->myclass();
		// fetchArray
	}
	
	public function test_fetchBoth()
	{
		$instance = new $this->myclass();
		// fetchBoth
	}
	
	public function test_fetchObject()
	{
		$instance = new $this->myclass();
		// fetchObject
	}
	
	public function test_getInsertId()
	{
		$instance = new $this->myclass();
		// getInsertId
	}
	
	public function test_getRowsNum()
	{
		$instance = new $this->myclass();
		// getRowsNum
	}
	
	public function test_getAffectedRows()
	{
		$instance = new $this->myclass();
		// getAffectedRows
	}
	
	public function test_close()
	{
		$instance = new $this->myclass();
		// close
	}
	
	public function test_freeRecordSet()
	{
		$instance = new $this->myclass();
		// freeRecordSet
	}
	
	public function test_error()
	{
		$instance = new $this->myclass();
		// error
	}
	
	public function test_errno()
	{
		$instance = new $this->myclass();
		// errno
	}
	
	public function test_quoteString()
	{
		$instance = new $this->myclass();
		// quoteString
	}
	
	public function test_quote()
	{
		$instance = new $this->myclass();
		// quote
	}
	
	public function test_queryF()
	{
		$instance = new $this->myclass();
		// queryF
	}
	
	public function test_query()
	{
		$instance = new $this->myclass();
		// query
	}
	
	public function test_queryFromFile()
	{
		$instance = new $this->myclass();
		// queryFromFile
	}
	
	public function test_getFieldName()
	{
		$instance = new $this->myclass();
		// getFieldName
	}
	
	public function test_getFieldType()
	{
		$instance = new $this->myclass();
		// getFieldType
	}
	
	public function test_getFieldsNum()
	{
		$instance = new $this->myclass();
		// getFieldsNum
	}
	
}
