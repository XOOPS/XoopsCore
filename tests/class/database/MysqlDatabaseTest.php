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
        $this->markTestIncomplete();
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
        $this->markTestIncomplete();
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
        $this->markTestIncomplete();
	}
	
	public function test_fetchArray()
	{
		$instance = new $this->myclass();
		// fetchArray
        $this->markTestIncomplete();
	}
	
	public function test_fetchBoth()
	{
		$instance = new $this->myclass();
		// fetchBoth
        $this->markTestIncomplete();
	}
	
	public function test_fetchObject()
	{
		$instance = new $this->myclass();
		// fetchObject
        $this->markTestIncomplete();
	}
	
	public function test_getInsertId()
	{
		$instance = new $this->myclass();
		// getInsertId
        $this->markTestIncomplete();
	}
	
	public function test_getRowsNum()
	{
		$instance = new $this->myclass();
		// getRowsNum
        $this->markTestIncomplete();
	}
	
	public function test_getAffectedRows()
	{
		$instance = new $this->myclass();
		// getAffectedRows
        $this->markTestIncomplete();
	}
	
	public function test_close()
	{
		$instance = new $this->myclass();
		// close
        $this->markTestIncomplete();
	}
	
	public function test_freeRecordSet()
	{
		$instance = new $this->myclass();
		// freeRecordSet
        $this->markTestIncomplete();
	}
	
	public function test_error()
	{
		$instance = new $this->myclass();
		// error
        $this->markTestIncomplete();
	}
	
	public function test_errno()
	{
		$instance = new $this->myclass();
		// errno
        $this->markTestIncomplete();
	}
	
	public function test_quoteString()
	{
		$instance = new $this->myclass();
		// quoteString
        $this->markTestIncomplete();
	}
	
	public function test_quote()
	{
		$instance = new $this->myclass();
		// quote
        $this->markTestIncomplete();
	}
	
	public function test_queryF()
	{
		$instance = new $this->myclass();
		// queryF
        $this->markTestIncomplete();
	}
	
	public function test_query()
	{
		$instance = new $this->myclass();
		// query
        $this->markTestIncomplete();
	}
	
	public function test_queryFromFile()
	{
		$instance = new $this->myclass();
		// queryFromFile
        $this->markTestIncomplete();
	}
	
	public function test_getFieldName()
	{
		$instance = new $this->myclass();
		// getFieldName
        $this->markTestIncomplete();
	}
	
	public function test_getFieldType()
	{
		$instance = new $this->myclass();
		// getFieldType
        $this->markTestIncomplete();
	}
	
	public function test_getFieldsNum()
	{
		$instance = new $this->myclass();
		// getFieldsNum
        $this->markTestIncomplete();
	}
	
}
